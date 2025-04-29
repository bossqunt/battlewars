import { getRarityBadgeClass, getRarityLabel } from './rarity.js';
import { updatePlayerStats } from './playerStats.js';
import { getBattleHistory } from './battle.js';


export async function updateMonstersTable() {
  const container = document.getElementById('monsters-nearby');
  // Always show the empty state by default on first load
  if (container && container.children.length === 0) {
    container.innerHTML = '<div class="text-center text-sm text-muted-foreground py-4">🧭 There are no nearby monsters.</div>';
  }

  try {
    let url = 'api/getMonsters.php';
    const response = await fetch(url);
    const monsters = await response.json();

    container.innerHTML = '';

    if (response.ok && !monsters.error) {
      if (monsters.length === 0) {
        container.innerHTML = '<div class="text-center text-sm text-muted-foreground py-4">🧭 There are no nearby monsters.</div>';
        return;
      }

      monsters.forEach((monster, index) => {
        const monsterCard = createMonsterCard(monster, index);
        container.appendChild(monsterCard);
      });
    } else {
      container.innerHTML = `<div class="text-center text-sm text-red-500 py-4">⚠️ Failed to retrieve monsters: ${monsters.error || 'Unknown error'}</div>`;
    }
  } catch (error) {
    console.error('Error fetching monsters:', error);
    const container = document.getElementById('monsters-nearby');
    container.innerHTML = '<div class="text-center text-sm text-red-500 py-4">⚠️ Error fetching monsters. Please try again later.</div>';
  }
}

export function createMonsterCard(monster, index) {
  const monsterCard = document.createElement('div');
  monsterCard.className = 'flex flex-row justify-between items-center border p-2 rounded-md monster-card mb-2';
  monsterCard.id = `monster-${index}`;

  // console.log(monster.is_boss); // Debug log
  // Highlight boss monster card with red border if monster.is_boss is true/1/"1"
  let isBoss = (monster.is_boss === 1 || monster.is_boss === "1");
  if (isBoss) {
    monsterCard.style.setProperty('border', '2px solid rgb(29 78 216 / var(--tw-text-opacity, 1))', 'important');
    monsterCard.classList.remove('border');
  }

  // Left side: Name and HP
  const leftWrapper = document.createElement('div');
  leftWrapper.className = 'flex flex-col truncate max-w-[70%]';

  const nameDiv = document.createElement('div');
  nameDiv.className = 'text-[12px] font-medium' + (isBoss ? ' text-blue-500' : '');
  nameDiv.textContent = `${monster.name} (Lv. ${monster.level})`;

  const hpDiv = document.createElement('div');
  hpDiv.className = 'text-[11px] text-muted-foreground';
  hpDiv.textContent = `HP: ${monster.hp} / SPD: ${monster.speed} `;

  leftWrapper.appendChild(nameDiv);
  leftWrapper.appendChild(hpDiv);

  // Right side: Auto-Battle button
  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'rpg-button rpg-button-primary w-auto shrink-0 h-6 px-1.5 py-0.5 flex gap-1 items-center text-[10px] rounded-md';
  button.setAttribute('data-monster-id', monster.id);
  button.setAttribute('data-monster-index', index);
  button.addEventListener('click', battleMonster);

  // Add SVG sword icon
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
  svg.setAttribute('width', '24');
  svg.setAttribute('height', '24');
  svg.setAttribute('viewBox', '0 0 24 24');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('stroke', 'currentColor');
  svg.setAttribute('stroke-width', '2');
  svg.setAttribute('stroke-linecap', 'round');
  svg.setAttribute('stroke-linejoin', 'round');
  svg.classList.add('lucide', 'lucide-sword', 'h-2.5', 'w-2.5');

  const polyline = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
  polyline.setAttribute('points', '14.5 17.5 3 6 3 3 6 3 17.5 14.5');

  const line1 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
  line1.setAttribute('x1', '13');
  line1.setAttribute('x2', '19');
  line1.setAttribute('y1', '19');
  line1.setAttribute('y2', '13');

  const line2 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
  line2.setAttribute('x1', '16');
  line2.setAttribute('x2', '20');
  line2.setAttribute('y1', '16');
  line2.setAttribute('y2', '20');

  const line3 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
  line3.setAttribute('x1', '19');
  line3.setAttribute('x2', '21');
  line3.setAttribute('y1', '21');
  line3.setAttribute('y2', '19');

  svg.appendChild(polyline);
  svg.appendChild(line1);
  svg.appendChild(line2);
  svg.appendChild(line3);

  // Add button text
  const buttonText = document.createElement('span');
  buttonText.className = 'text-[10px]';
  buttonText.textContent = 'Battle';

  button.appendChild(svg);
  button.appendChild(buttonText);

  // Assemble card
  monsterCard.appendChild(leftWrapper);
  monsterCard.appendChild(button);

  return monsterCard;
}

export async function battleMonster(event) {
    const button = event.currentTarget;
    const monsterId = button.getAttribute('data-monster-id');
    const monsterIndex = button.getAttribute('data-monster-index');
  
    const monsterCard = document.getElementById(`monster-${monsterIndex}`);
    
    if (monsterCard) {
      monsterCard.remove();
  
      const container = document.getElementById('monsters-nearby');
  
      // ✅ Select all remaining monster cards in the container
      const remainingMonsters = container.querySelectorAll('.monster-card');
  
      if (remainingMonsters.length === 0) {
        container.innerHTML = `
          <div class="text-center text-sm text-muted-foreground py-4">
            🧭 There are no nearby monsters.
          </div>`;
      }
    }
 
  

  // Pre-fetch level up elements (or do it inside the if/else if preferred)
  const levelUpContainer = document.getElementById("level-up");
  const levelUpMessageElement = document.getElementById("level-up-message");

  const lootSection = document.getElementById("loot-section");
  const lootList = document.getElementById("loot-list");

  try {
    const response = await fetch(`api/battleMonster.php?playerId=${playerId}&monsterId=${monsterId}`);
    const result = await response.json();

    // Populate combat log
    const battleLogArray = Array.isArray(result.battle) ? result.battle : [];
    const logHtml = battleLogArray.map(log => `<p class="text-gray-600">${log}</p>`).join('');
    document.getElementById('battle-log-content').innerHTML = logHtml;

    // Battle outcome
    const battleOutcome = document.getElementById('battle-outcome');
    if (result.victory === 1) {
      battleOutcome.innerHTML = `
        <p class="text-lg font-semibold text-green-600">Victory!</p>
        <p class="text-gray-700">${result.result || 'You defeated the monster!'}</p>
      `;
    } else {
      battleOutcome.innerHTML = `
        <p class="text-lg font-semibold text-red-600">Defeat!</p>
        <p class="text-gray-700">${result.result || 'You were defeated by the monster.'}</p>
      `;
    }

    // Rewards
    document.getElementById('exp-reward').innerHTML = `
      <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
      <span>Gained <strong class="text-purple-600">${result.exp || 0}</strong> Experience Points</span>
    `;

    document.getElementById('gold-reward').innerHTML = `
      <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
      <span>Collected <strong class="text-yellow-600">${result.gold || 0}</strong> Gold Coins</span>
    `;

    // Ensure rewards section is visible if there are rewards
    const lootMessageElement = document.getElementById('loot-message');
    const expRewardElement = document.getElementById('exp-reward');
    const goldRewardElement = document.getElementById('gold-reward');
    const rewardsSection = lootMessageElement?.closest('div');

    const hasExp = result.exp && result.exp > 0;
    const hasGold = result.gold && result.gold > 0;
    const hasLootMessage = result.loot_message && result.loot_message.trim() !== '';

    if (rewardsSection) {
      if (hasExp || hasGold || hasLootMessage) {
        rewardsSection.style.display = 'block';
      } else {
        rewardsSection.style.display = 'none';
      }
    }

    if (levelUpContainer && levelUpMessageElement) {
      if (result.levelup === true) {
        // Level Up occurred: Show the section and set the message
        levelUpContainer.style.display = "block";
        levelUpMessageElement.textContent = "Congratulations! You have leveled up!!";
      } else {
        // NO Level Up occurred: Hide the section and clear any old message
        levelUpContainer.style.display = "none";
        levelUpMessageElement.textContent = "";
      }
    }

    // Clear previous loot
    lootList.innerHTML = "";
    lootSection.style.display = "none";

    if (result.items_dropped && result.items_dropped.length > 0) {
      lootSection.style.display = "block";
      result.items_dropped.forEach(item => {
        const li = document.createElement("li");
        li.className = "text-center my-2";

        // Create the badge element (on top)
        const badgeEl = document.createElement("span");
        badgeEl.className = `inline-block mb-1 px-2 py-0.5 rounded-full text-xs font-semibold ${getRarityBadgeClass(item.rarity)}`;
        badgeEl.textContent = getRarityLabel(item.rarity);

        li.appendChild(badgeEl);

        // Add loot text
        const lootText = document.createElement("div");
        lootText.className = "text-sm text-gray-700 mt-1";
        lootText.textContent = `You looted ${item.name}`;
        li.appendChild(lootText);

        lootList.appendChild(li);
      });
    }

    // Show modal
    const battleModal = document.getElementById('battle-modal');
    battleModal.classList.remove('hidden');

    // Update player stats UI
    updatePlayerStats();

    // update battle history
    if (typeof playerId !== 'undefined') {
        getBattleHistory(playerId);
      }

  } catch (error) {
    console.error('Error during battle:', error);
  }
}
