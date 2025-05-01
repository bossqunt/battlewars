import { updateGridLocation } from './grid.js';
import { updateMonstersTable } from './monsters.js';
import { showToast } from './ui.js';

export async function travelPlayer() {
  const travelButton = document.getElementById('travel-button');
  travelButton.disabled = true;
  travelButton.classList.remove('animate-bounce', 'ease-linear')

  try {
    const response = await fetch('api/travelPlayer.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: playerId })
    });

    const data = await response.json();

    if (response.ok && !data.error) {
      startCooldown(travelButton, data.remainingCooldown || 10);
      updateMonstersTable();
      showToast('You have successfully traveled!', 'success', 3000);
    } else {
      handleCooldownError(travelButton, data);
      showToast('Please wait to travel again', 'error', 3000);
    }
  } catch (error) {
    console.error('Error:', error);
    travelButton.disabled = false;
    travelButton.classList.add('animate-bounce', 'ease-linear')
  }
}

export function startCooldown(button, countdown) {
  button.textContent = `Travel (${countdown})`;
  const countdownInterval = setInterval(() => {
    countdown--;
    button.textContent = `Travel (${countdown})`;
    

    if (countdown <= 0) {
      clearInterval(countdownInterval);
      button.textContent = 'Travel';
      button.disabled = false;
      button.classList.add('animate-bounce', 'ease-linear')
    }
  }, 1000);
}

export function handleCooldownError(button, data) {
  if (data.error === 'Cooldown period active') {
    startCooldown(button, data.remainingCooldown);
  } else {
    console.error('Failed to travel:', data.error);
    button.disabled = false;
  }
}
