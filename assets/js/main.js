
// Function to show Toast notifications
function showToast(message, type) {
  const toastContainer = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.classList.add(
    'p-4', 'pl-5', 'rounded-md', 'shadow-md', 'bg-white', 'text-sm',
    'w-72', 'max-w-full', 'relative', 'border-l-4', 'flex', 'items-start', 'gap-2'
  );

  // Color based on type
  let borderColor = 'border-blue-500';
  let iconColor = 'text-blue-500';

  if (type === 'success') {
    borderColor = 'border-green-500';
    iconColor = 'text-green-500';
  } else if (type === 'error') {
    borderColor = 'border-red-500';
    iconColor = 'text-red-500';
  }

  toast.classList.add(borderColor);

  toast.innerHTML = `
    <div class="${iconColor} pt-0.5">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
      </svg>
    </div>
    <div class="flex-1 text-gray-800">${message}</div>
    <button onclick="this.closest('div').remove();" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  `;

  toastContainer.appendChild(toast);

  // Auto-remove after 4 seconds
  setTimeout(() => {
    toast.remove();
  }, 4000);
}
