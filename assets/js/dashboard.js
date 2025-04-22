import { showToast } from './ui.js';
import { updatePlayerStats, fetchPlayerData } from './playerStats.js';
import { updateGridLocation, updateGridOwner } from './grid.js';
import { travelPlayer, startCooldown, handleCooldownError } from './travel.js';
import { takeOwnership } from './ownership.js';
import { updateMonstersTable, createMonsterCard, battleMonster } from './monsters.js';
import { getBattleHistory, clearBattleModalRewards } from './battle.js';
import { loadWorldEvents } from './worldEvents.js';

async function updateOnlinePlayers() {
  try {
    const response = await fetch('/bw2/api/getOnlinePlayers.php');
    const data = await response.json();
 
    const onlineCountElem = document.getElementById('online-player-count');
    if (onlineCountElem) {
      onlineCountElem.textContent = data.count;
    } else {
      console.log('Element #online-player-count not found');
    }
  } catch (e) {
    console.error('Error fetching online players:', e);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const travelBtn = document.getElementById('travel-button');
  if (travelBtn) travelBtn.addEventListener('click', travelPlayer);

  const ownershipBtn = document.getElementById('take-ownership-button');
  if (ownershipBtn) ownershipBtn.addEventListener('click', () => {
    clearBattleModalRewards();
    takeOwnership();
  });

  const closeBattleBtn = document.getElementById('close-battle');
  if (closeBattleBtn) closeBattleBtn.addEventListener('click', () => {
    document.getElementById('battle-modal').classList.add('hidden');
    clearBattleModalRewards();
  });

  const closeModalBtn = document.getElementById('close-modal');
  if (closeModalBtn) closeModalBtn.addEventListener('click', () => {
    document.getElementById('battle-modal').classList.add('hidden');
    clearBattleModalRewards();
  });

  loadWorldEvents();

  // Use setInterval with an async callback for proper async updates
  setInterval(async () => {
    await loadWorldEvents();
  }, 17000); // 17 seconds (between 15-20s, adjust as desired)

  updateOnlinePlayers();
  setInterval(updateOnlinePlayers, 15000); // update every 15s

});

getBattleHistory(playerId);