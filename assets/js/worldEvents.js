export async function loadWorldEvents() {
  try {
    const response = await fetch('/bw2/api/getWorldEvents.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();
    const container = document.getElementById('world-events');

    if (data.events && data.events.length > 0) {
      container.innerHTML = `
      <table class="w-full text-xs table-fixed">
        <tbody>
          ${data.events.map(event => `
            <tr class="">
              <td class="pr-1 text-muted-foreground w-48 whitespace-nowrap">
                ${new Date(event.timestamp).toLocaleString()}
              </td>
              <td class="text-foreground">
                ${event.event}
              </td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;
    } else {
      container.innerHTML = '<p class="text-xs text-muted-foreground">No world events currently active.</p>';
    }
  } catch (error) {
    console.error('Failed to load world events:', error);
    document.getElementById('world-events').innerHTML =
      '<p class="text-xs text-red-500">Error loading world events.</p>';
  }
}
