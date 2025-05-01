import { updateGridLocation } from './grid.js';
import { showToast } from './ui.js';


setInterval(updatePlayerStats, 5000); // Poll every 5 seconds
updatePlayerStats(); // Initial call to fetch and display data

async function updatePlayerStats() {
  // console.log("Calling updatePlayerStats"); 
  const playerData = await fetchPlayerData();
  if (playerData) {
    //console.log("Player data:", playerData); // Debug log
    document.getElementById('player-name').textContent = playerData.name;
    document.getElementById('player-level').textContent = 'Level ' + playerData.level;
    //  document.getElementById('player-image').src = playerData.image_path;

    updateProgressBar(document.getElementById('exp-bar'), document.getElementById('exp-value'), playerData.exp, playerData.exp_req);
    updateProgressBar(document.getElementById('hp-bar'), document.getElementById('hp-value'), playerData.c_hp, playerData.max_hp);
    updateProgressBar(document.getElementById('st-bar'), document.getElementById('st-value'), playerData.stamina, 60);

    // Update the gold value dynamically
    document.getElementById('gold-value').textContent = playerData.gold;
    // Only update grid location if the grid element exists
    if (document.getElementById('grid-controller')) {
      updateGridLocation(playerData);
    }
 // Update the inventory count dynamically
 if (playerData.inventoryCount && playerData.inventoryCount.length > 0) {
  document.getElementById('inventorycount').textContent = playerData.inventoryCount[0].count;
} else {
  document.getElementById('inventorycount').textContent = 0;
}
  }
}

async function fetchPlayerData() {
  try {
    const response = await fetch('api/getPlayer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: playerId })
    });

    const playerData = await response.json();
    return playerData;
  } catch (error) {
    console.error('Error fetching player data:', error);
    return null;
  }
}

function updateProgressBar(barElement, valueElement, value, maxValue) {
  // Prevent division by zero and negative values

  let percentage = 0;
  if (maxValue > 0) {
    percentage = Math.max(0, Math.min(100, (value / maxValue) * 100));
  }
  barElement.style.width = percentage + '%';
  barElement.setAttribute('aria-valuenow', percentage);
  valueElement.textContent = `${value} / ${maxValue}`;

  // Remove Bootstrap color classes if present
  barElement.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');

  // Ensure Tailwind green class is present
  if (!barElement.classList.contains('bg-green-500')) {
    barElement.classList.add('bg-green-500');
  }
}

export { updatePlayerStats, fetchPlayerData };

