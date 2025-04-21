import { updateGridLocation } from './grid.js';

setInterval(updatePlayerStats, 5000); // Poll every 5 seconds
updatePlayerStats(); // Initial call to fetch and display data

async function updatePlayerStats() {
  // console.log("Calling updatePlayerStats"); 
  const playerData = await fetchPlayerData();
  if (playerData) {
    //console.log("Player data:", playerData); // Debug log
    document.getElementById('player-name').textContent = playerData.name;
    document.getElementById('player-level').textContent = playerData.level;
    // document.getElementById('player-image').src = playerData.image_path;

    updateProgressBar(document.getElementById('exp-bar'), document.getElementById('exp-value'), playerData.exp, playerData.exp_req);
    updateProgressBar(document.getElementById('hp-bar'), document.getElementById('hp-value'), playerData.c_hp, playerData.max_hp);
    updateProgressBar(document.getElementById('st-bar'), document.getElementById('st-value'), playerData.stamina, 60);

    // Update the gold value dynamically
    document.getElementById('gold-value').textContent = playerData.gold;
    updateGridLocation(playerData); // Call updateGridLocation here
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
    const response = await fetch('/bw2/api/getPlayer.php', {
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

function updateProgressBar(barElement, valueElement, value, maxValue, type = null) {
  const percentage = (value / maxValue) * 100;
  barElement.style.width = percentage + '%';
  barElement.setAttribute('aria-valuenow', percentage);
  valueElement.textContent = `${value} / ${maxValue}`;
}

export { updatePlayerStats, fetchPlayerData };

