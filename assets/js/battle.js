import { updatePlayerStats } from './playerStats.js';

export function getBattleHistory(playerId) {
  const endpoint = `/bw2/api/getPlayerBattleHistory.php`;
  updatePlayerStats();

  fetch(`${endpoint}?id=${playerId}`)
    .then(response => response.json())
    .then(data => {
      const battleLog = document.getElementById('battle-log');
      if (!battleLog) {
        console.warn('Battle log container not found.');
        return;
      }

      battleLog.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        const emptyLog = document.createElement('div');
        emptyLog.className = 'rpg-battle-log-entry rpg-battle-log-info text-[10px]';
        emptyLog.textContent = 'No recent battles';
        battleLog.appendChild(emptyLog);
        return;
      }

      data.forEach(battle => {
        const entry = document.createElement('div');
        entry.className = 'rpg-battle-log-entry text-[10px]';
        entry.textContent = `${battle.result}.`;
        battleLog.appendChild(entry);
      });
    })
    .catch(error => {
      console.error('Error fetching battle history:', error);
      const battleLog = document.querySelector('.battle-log .space-y-0.5');
      if (battleLog) {
        battleLog.innerHTML = '<div class="rpg-battle-log-entry text-red-500 text-[10px]">Failed to fetch battle history. Please try again later.</div>';
      }
    });
}

export function clearBattleModalRewards() {
  const battleOutcome = document.getElementById('battle-outcome');
  const logContent = document.getElementById('battle-log-content');
  const lootMessageElement = document.getElementById('loot-message');
  const expRewardElement = document.getElementById('exp-reward');
  const goldRewardElement = document.getElementById('gold-reward');
  const rewardsSection = lootMessageElement?.closest('div');

  if (battleOutcome) battleOutcome.innerHTML = '';
  if (logContent) logContent.innerHTML = '';
  if (lootMessageElement) lootMessageElement.innerHTML = '';
  if (expRewardElement) expRewardElement.innerHTML = '';
  if (goldRewardElement) goldRewardElement.innerHTML = '';
  if (rewardsSection) rewardsSection.style.display = 'none';
}
