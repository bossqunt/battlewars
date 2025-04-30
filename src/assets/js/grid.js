// TODO: When taking ownership, update playerData.areaOwner in JS with the new owner info and call updateGridOwner(playerData) (and updateGridLocation(playerData) if needed) so the UI updates without a page reload.


// Set this variable to control the grid gap size (in px)
const GRID_GAP_SIZE = 6; // Change this value to set the gap between cells

export async function updateGridLocation(playerData) {
  const gridContainer = document.getElementById('grid-container');
  const parentContainer = gridContainer?.parentElement; // Get parent container
  const travelDetails = document.getElementById('travel-details');
  if (gridContainer) gridContainer.innerHTML = '';
  if (travelDetails) travelDetails.innerHTML = '';

  // Responsive grid: fill parent, fixed gap

  if (gridContainer) {
    gridContainer.style.display = 'grid';
    gridContainer.style.gridTemplateColumns = 'repeat(9, 1fr)';
    gridContainer.style.gridTemplateRows = 'repeat(9, 1fr)';
    gridContainer.style.gap = `${GRID_GAP_SIZE}px`;
    gridContainer.style.width = '100%';
    gridContainer.style.height = '100%';
    gridContainer.style.setProperty('--grid-cell-size', '');

    // Make parent container responsive as well
    if (parentContainer) {
      // parentContainer.style.width = '100%';    // Remove this
      // parentContainer.style.height = '100%';   // Remove this
      parentContainer.style.maxWidth = 'min(70vw, 70vh)';
      parentContainer.style.maxHeight = 'min(70vw, 70vh)';
      parentContainer.style.minWidth = '500px';    // Prevents grid from becoming too small
      parentContainer.style.minHeight = '500px';   // Prevents grid from becoming too small
      parentContainer.style.aspectRatio = '1 / 1';
      parentContainer.style.display = 'flex';
      parentContainer.style.alignItems = 'center';
      parentContainer.style.justifyContent = 'center';
    }
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
    playerGridLocation.classList.add('text-xs');

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
  const ownerProfileButton = document.getElementById('owner-profile-button') || null;



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
      
      // Fix: Only show guild display if owner has a guild, otherwise hide and clear it
      if (owner.guild && ownerGuildDisplay) {
        ownerGuildDisplay.classList.remove('hidden');
        ownerGuildDisplay.innerHTML = `Guild: ${owner.guild.name} <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-1 py-0 rounded ml-1 align-middle">${owner.guild.tag}</span>`;
      } else if (ownerGuildDisplay) {
        ownerGuildDisplay.classList.add('hidden');
        ownerGuildDisplay.innerHTML = '';
      }

      // Show ownership button if player is not the owner
      if (ownershipButton && owner.player_id == playerData.id) {
        ownershipButton.classList.add('hidden');
      } else {
        ownershipButton.classList.remove('hidden');
        ownerProfileButton.classList.remove('hidden');
        ownerProfileButton.onclick = () => {
          window.location.href = `/profile.php?id=${owner.player_id}`;
        };

      }
      ownerDisplay.appendChild(nameSpan);
      ownerDisplay.appendChild(levelSpan);
      

    } else {
      if (ownerGuildDisplay) {
        ownerGuildDisplay.classList.add('hidden');
        ownerGuildDisplay.innerHTML = '';
      }
      if (ownerImage) {
        ownerImage.classList.add('hidden');
      }
      ownerDisplay.textContent = 'Nobody owns this tile.';
      if (ownershipButton) {
        ownershipButton.classList.remove('hidden');
      }

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
