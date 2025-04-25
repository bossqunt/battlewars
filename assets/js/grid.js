export async function updateGridLocation(playerData) {
  const gridContainer = document.getElementById('grid-container');
  const travelDetails = document.getElementById('travel-details');
  gridContainer.innerHTML = '';
  travelDetails.innerHTML = '';

  const gridWidth = 9;
  const gridHeight = 9;
  const playerLocation = playerData.area[0];
  const locationDisplay = document.getElementById('location-text');
  let onBossTile = false; // Use let so we can update it

  if (locationDisplay && playerLocation) {
    locationDisplay.textContent = `X: ${playerLocation.x} Y: ${playerLocation.y}`;
  }

  updateGridOwner(playerData);


  // Assume playerData.boss = { x, y, alive: true, image_path }
  const boss_x = playerData.area[0].boss_x;
  const boss_y = playerData.area[0].boss_y;


  for (let row = 0; row < gridHeight; row++) {
    for (let col = 0; col < gridWidth; col++) {
      const cell = document.createElement('div');
      cell.classList.add('grid-cell');

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
  const gridOwner = playerData.areaOwner;
  const playerLocation = playerData.area[0];
  const ownerDisplay = document.getElementById('owner-text');

  if (ownerDisplay && gridOwner) {
    const owner = gridOwner.find(owner => owner.x === playerLocation.x && owner.y === playerLocation.y);

    if (owner) {
      ownerDisplay.innerHTML = '';

      const textSpan = document.createElement('span');
      textSpan.textContent = `Grid Owner: ${owner.name} (Level ${owner.level})`;

      const img = document.createElement('img');
      img.src = owner.image_path;
      img.alt = `${owner.name}'s image`;
      img.classList.add('w-5', 'h-5', 'rounded-full', 'mr-1');

      ownerDisplay.appendChild(img);
      ownerDisplay.appendChild(textSpan);
    } else {
      ownerDisplay.textContent = 'Nobody owns this tile.';
    }
  }
}
