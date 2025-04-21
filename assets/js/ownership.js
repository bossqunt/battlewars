import { fetchPlayerData, updatePlayerStats } from './playerStats.js';
import { showToast } from './ui.js';
import { clearBattleModalRewards } from './battle.js';

export async function takeOwnership() {
  // --- Clear loot section and loot list before showing PvP modal ---
  const lootSection = document.getElementById('loot-section');
  const lootList = document.getElementById('loot-list');
  if (lootSection) lootSection.style.display = 'none';
  if (lootList) lootList.innerHTML = '';

  try {
    const playerData = await fetchPlayerData();
    const playerLocation = playerData.area[0];
    const areaOwner = playerData.areaOwner.find(
      owner => owner.x === playerLocation.x && owner.y === playerLocation.y
    );

    // If the tile is already owned by the player
    if (areaOwner && playerData.id === areaOwner.player_id) {
      showToast('You already own this tile!', 'info', 3000);
      return;
    }

    // If the tile is unowned
    if (!areaOwner) {
      const response = await fetch('/bw2/api/takeOwnership.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          playerId: playerData.id,
          x: playerLocation.x,
          y: playerLocation.y,
          area_id: playerLocation.area_id
        })
      });

      // Clear any previous loot message before showing the new one
      const lootMessageElement = document.getElementById('loot-message');
      lootMessageElement.innerHTML = '';  // Clear previous loot message
      // If the tile is owned by another player, show a modal to be sure they mean to take ownership from player
      const result = await response.json();

      if (response.ok && !result.error) {
        updatePlayerStats();
      } else {
        console.error('Failed to take ownership:', result.error);
      }
    } else {
      // Show modal to confirm PvP battle
      clearBattleModalRewards();
      document.getElementById('player-battle-modal').classList.remove('hidden');
    
      // Set text dynamically (optional)
      document.getElementById('player-battle-text').innerText =
        `This tile is owned by ${areaOwner.name} (Level ${areaOwner.level}). Do you want to challenge them for control?`;
    
      // Attach event listeners
      const confirmBtn = document.getElementById('confirm-player-battle');
      const cancelBtn = document.getElementById('cancel-player-battle');
    
      // Remove old listeners (optional clean-up)
      confirmBtn.replaceWith(confirmBtn.cloneNode(true));
      cancelBtn.replaceWith(cancelBtn.cloneNode(true));
    
      document.getElementById('confirm-player-battle').addEventListener('click', async () => {
        // Always clear rewards before setting new ones
        const lootMessageElement = document.getElementById('loot-message');
        const expRewardElement = document.getElementById('exp-reward');
        const goldRewardElement = document.getElementById('gold-reward');
        const rewardsSection = lootMessageElement.closest('div');
        lootMessageElement.innerHTML = '';
        expRewardElement.innerHTML = '';
        goldRewardElement.innerHTML = '';
        if (rewardsSection) rewardsSection.style.display = 'none';
        clearBattleModalRewards(); 
        document.getElementById('player-battle-modal').classList.add('hidden');
        
        // Trigger the battle
        try {
          const battleResponse = await fetch('/bw2/api/BattlePlayer.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              playerId: playerData.id,
              opponentId: areaOwner.player_id,
              area_id: playerLocation.area_id,
              x: playerLocation.x,
              y: playerLocation.y
            })
          });
    
          const result = await battleResponse.json();
    
          const battleOutcome = document.getElementById('battle-outcome');
          const logContent = document.getElementById('battle-log-content');
          const loot_message = document.getElementById('loot-message');
    
          // Optional: If you want to reuse the monster modal layout for PvP
          const battleLogArray = Array.isArray(result.battle) ? result.battle : [];
          logContent.innerHTML = battleLogArray.map(log => `<p class="text-gray-600">${log}</p>`).join('');
    
          if (result.victory === 1) {
            battleOutcome.innerHTML = `
              <p class="text-lg font-semibold text-green-600">Victory!</p>
              <p class="text-gray-700">${result.result || 'You defeated the opponent!'}</p>
            `;
          } else {
            battleOutcome.innerHTML = `
              <p class="text-lg font-semibold text-red-600">Defeat!</p>
              <p class="text-gray-700">${result.result || 'You were defeated by the opponent.'}</p>
            `;
          }
          const lootMessageElement = document.getElementById('loot-message');

          const expRewardElement = document.getElementById('exp-reward');
          const goldRewardElement = document.getElementById('gold-reward');
          const rewardsSection = lootMessageElement.closest('div'); // Grabs the parent div
          
          const hasLootMessage = result.loot_message && result.loot_message.trim() !== '';
          const expVal = parseInt(result.exp_gain || result.exp || 0);
          const goldVal = parseInt(result.gold_gain || result.gold || 0);
          const hasExp = !isNaN(expVal) && expVal > 0;
          const hasGold = !isNaN(goldVal) && goldVal > 0;
          
          if (!hasLootMessage && !hasExp && !hasGold) {
            rewardsSection.style.display = 'none';
          } else {
            rewardsSection.style.display = 'block';
            lootMessageElement.innerHTML = hasLootMessage ? result.loot_message : '';
            expRewardElement.innerHTML = hasExp ? `EXP: ${expVal}` : '';
            goldRewardElement.innerHTML = hasGold ? `Gold: ${goldVal}` : '';
          }
    
          // Show result modal (reuse the existing one)
          document.getElementById('battle-modal').classList.remove('hidden');
          updatePlayerStats();
    
        } catch (error) {
          console.error('Error in BattlePlayer API:', error);
          showToast('An error occurred during the battle.', 'error', 3000);
        }
      });
    
      document.getElementById('cancel-player-battle').addEventListener('click', () => {
        document.getElementById('player-battle-modal').classList.add('hidden');
      });
    }
    
  } catch (err) {
    console.error('Error in takeOwnership():', err);
  }
}
