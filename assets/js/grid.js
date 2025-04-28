// Set this variable to control the grid gap size (in px)
const GRID_GAP_SIZE = 4; // Change this value to set the gap between cells

export async function updateGridLocation(playerData) {
  const gridContainer = document.getElementById('grid-container');
  const travelDetails = document.getElementById('travel-details');
  if (gridContainer) gridContainer.innerHTML = '';
  if (travelDetails) travelDetails.innerHTML = '';

  // Responsive grid: fill parent, fixed gap
  if (gridContainer) {
    gridContainer.style.display = 'grid';
    gridContainer.style.gridTemplateColumns = 'repeat(9, 1fr)';
    gridContainer.style.gridTemplateRows = 'repeat(9, 1fr)';
    gridContainer.style.gap = `${GRID_GAP_SIZE}px`;
    // Remove fixed width/height
    gridContainer.style.width = '';
    gridContainer.style.height = '';
    gridContainer.style.setProperty('--grid-cell-size', '');
  }

  const gridWidth = 9;
  const gridHeight = 9;
  const playerLocation = playerData.area[0];
  // FOR REMOVAL
  const locationDisplay = document.getElementById('location-text');
  const playerGridLocation = document.getElementById('player-grid-location');

  let onBossTile = false; // Use let so we can update it

  // Required for V2 Dashboard
  if (playerGridLocation && playerLocation) {
    playerGridLocation.textContent = `X: ${playerLocation.x} Y: ${playerLocation.y} Z: ${playerLocation.area_id}`;
  }

  updateGridOwner(playerData);

  // Assume playerData.boss = { x, y, alive: true, image_path }
  const boss_x = playerData.area[0].boss_x;
  const boss_y = playerData.area[0].boss_y;

  for (let row = 0; row < gridHeight; row++) {
    for (let col = 0; col < gridWidth; col++) {
      const cell = document.createElement('div');
      cell.classList.add('grid-cell');
      // Remove fixed cell size, let grid handle sizing
      cell.style.width = '';
      cell.style.height = '';

      // Boss indicator (always show boss tile)
      if (boss_x === col && boss_y === row) {
        cell.classList.add('boss-location');
        cell.textContent = 'B';
      }

      // Player indicator (overrides boss if on same tile)
      if (playerLocation.x === col && playerLocation.y === row) {
        cell.classList.add('player-location');
        cell.textContent = 'P';
        // If player is on boss tile, show both indicators
        if (boss_x === col && boss_y === row) {
          cell.textContent = 'PvB';
          onBossTile = true; // Set flag if player is on boss tile
        }
      }

      const areaOwner = playerData.areaOwner.find(owner => owner.x === col && owner.y === row);
      if (areaOwner) {
        cell.style.backgroundImage = `url('${areaOwner.image_path}')`;
        cell.style.backgroundSize = 'cover';
        cell.style.backgroundPosition = 'center';
      }

      gridContainer.appendChild(cell);
    }
  }

  // After rendering the grid, update monsters table with boss flag
  if (typeof updateMonstersTable === 'function') {
    updateMonstersTable(onBossTile);
  }
}

export function updateGridOwner(playerData) {
  if (!playerData || !playerData.areaOwner || !playerData.area || !playerData.area[0]) return;
  const gridOwner = playerData.areaOwner;
  const playerLocation = playerData.area[0];
  const ownerDisplay = document.getElementById('owner-text') || null;
  const ownerImage = document.getElementById('owner-image') || null;
  const ownerGuildDisplay = document.getElementById('owner-guild-text') || null;
  const ownershipButton = document.getElementById('take-ownership-button') || null;
  console.log('Grid Owner:', gridOwner);
  console.log('Player Location:', playerLocation);
  console.log('Owner Display:', ownerDisplay);
  console.log('Owner Image:', ownerImage);
  console.log('Owner Guild Display:', ownerGuildDisplay);
  console.log('Ownership Button:', ownershipButton);


  if (ownerDisplay && gridOwner) {
    const owner = gridOwner.find(owner => owner.x === playerLocation.x && owner.y === playerLocation.y);

    if (owner) {
      ownerDisplay.innerHTML = '';

      // Owner name in bold, level normal
      const nameSpan = document.createElement('span');
      nameSpan.textContent = owner.name;
      nameSpan.classList.add('font-bold');

      const levelSpan = document.createElement('span');
      levelSpan.classList.add('text-gray-700', 'text-xs', 'text-muted-foreground');
      levelSpan.textContent = ` (Level ${owner.level})`;

      if (ownerImage) {
        ownerImage.classList.remove('hidden');
        ownerImage.src = owner.image_path;
        ownerImage.classList.add('w-10', 'h-10', 'rounded-full', 'mr-1');
      }
      
      if (owner.guild && ownerGuildDisplay) {
        ownerGuildDisplay.classList.remove('hidden');
        // Smaller badge to fit with font-size
        ownerGuildDisplay.innerHTML = `Guild: ${owner.guild.name} <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-1 py-0 rounded ml-1 align-middle">${owner.guild.tag}</span>`;
      }

      // Show ownership button if player is not the owner
      if (ownershipButton && owner.player_id == playerData.id) {
        ownershipButton.classList.add('hidden');
      } else {
        ownershipButton.classList.remove('hidden');
      }

      ownerDisplay.appendChild(nameSpan);
      ownerDisplay.appendChild(levelSpan);
    } else {
      ownerImage.classList.add('hidden');
      ownerDisplay.textContent = 'Nobody owns this tile.';
    }
  }
}

// Make sure updateGridOwner is available globally for travel.js
window.updateGridOwner = updateGridOwner;

export function updateGridCellSize() {
  // No need to set cell size or grid size, let CSS Grid handle it responsively
}

window.addEventListener('resize', updateGridCellSize);
window.addEventListener('DOMContentLoaded', updateGridCellSize);
