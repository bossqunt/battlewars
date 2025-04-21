export function showToast(message, type, duration = 3000) {
  // Create toast container if it doesn't exist
  let toastContainer = document.getElementById('toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.classList.add('fixed', 'top-4', 'right-4', 'space-y-4', 'z-50');
    document.body.appendChild(toastContainer);
  }

  // Toast wrapper
  const toast = document.createElement('div');
  toast.className = 'flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800';
  toast.setAttribute('role', 'alert');

  // Icon wrapper and SVG
  const icon = document.createElement('div');
  icon.className = 'inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg';
  let svg = '';
  if (type === 'success') {
    icon.classList.add('text-green-500', 'bg-green-100', 'dark:bg-green-800', 'dark:text-green-200');
    svg = `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg><span class="sr-only">Check icon</span>`;
  } else if (type === 'error') {
    icon.classList.add('text-red-500', 'bg-red-100', 'dark:bg-red-800', 'dark:text-red-200');
    svg = `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg><span class="sr-only">Error icon</span>`;
  } else if (type === 'warning') {
    icon.classList.add('text-orange-500', 'bg-orange-100', 'dark:bg-orange-700', 'dark:text-orange-200');
    svg = `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg><span class="sr-only">Warning icon</span>`;
  }
  icon.innerHTML = svg;

  // Toast message
  const text = document.createElement('div');
  text.className = 'ms-3 text-sm font-normal';
  text.textContent = message;

  // Close button
  const closeBtn = document.createElement('button');
  closeBtn.type = 'button';
  closeBtn.className = 'ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700';
  closeBtn.setAttribute('aria-label', 'Close');
  closeBtn.innerHTML = `<span class="sr-only">Close</span><svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>`;
  closeBtn.onclick = () => {
    toast.classList.add('opacity-0');
    setTimeout(() => toast.remove(), 300);
  };

  // Append icon, text, and close button to the toast
  toast.appendChild(icon);
  toast.appendChild(text);
  toast.appendChild(closeBtn);

  // Append the toast to the container
  toastContainer.appendChild(toast);

  // Fade in the toast
  setTimeout(() => {
    toast.classList.add('opacity-100');
  }, 100);

  // Remove toast after the specified duration
  setTimeout(() => {
    toast.classList.remove('opacity-100');
    setTimeout(() => toast.remove(), 300);
  }, duration);
}
