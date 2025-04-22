export function showToast(message, type = 'normal', duration = 3000) {
  // Create toast container if it doesn't exist
  let toastContainer = document.getElementById('toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.className = 'fixed top-4 right-4 space-y-3 z-50';
    document.body.appendChild(toastContainer);
  }

  // Toast wrapper
  const toast = document.createElement('div');
  toast.className = 'max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg transition-opacity opacity-0';
  toast.setAttribute('role', 'alert');
  toast.setAttribute('tabindex', '-1');

  // Icon SVG and color
  let svg = '';
  let iconClass = '';
  let labelId = '';
  switch (type) {
    case 'success':
      iconClass = 'text-teal-500';
      labelId = 'hs-toast-success-example-label';
      svg = `<svg class="shrink-0 size-4 ${iconClass} mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
      </svg>`;
      break;
    case 'error':
      iconClass = 'text-red-500';
      labelId = 'hs-toast-error-example-label';
      svg = `<svg class="shrink-0 size-4 ${iconClass} mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
      </svg>`;
      break;
    case 'warning':
      iconClass = 'text-yellow-500';
      labelId = 'hs-toast-warning-example-label';
      svg = `<svg class="shrink-0 size-4 ${iconClass} mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
      </svg>`;
      break;
    case 'info':
    case 'normal':
    default:
      iconClass = 'text-blue-500';
      labelId = 'hs-toast-normal-example-label';
      svg = `<svg class="shrink-0 size-4 ${iconClass} mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
      </svg>`;
      break;
  }

  // Toast content
  toast.innerHTML = `
    <div class="flex p-4">
      <div class="shrink-0">${svg}</div>
      <div class="ms-3">
        <p id="${labelId}" class="text-sm text-gray-700">${message}</p>
      </div>
      <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
      </button>
    </div>
  `;

  // Close button logic
  const closeBtn = toast.querySelector('button[aria-label="Close"]');
  closeBtn.onclick = () => {
    toast.classList.remove('opacity-100');
    toast.classList.add('opacity-0');
    setTimeout(() => toast.remove(), 300);
  };

  // Append the toast to the container
  toastContainer.appendChild(toast);

  // Fade in the toast
  setTimeout(() => {
    toast.classList.add('opacity-100');
    toast.classList.remove('opacity-0');
  }, 50);

  // Remove toast after the specified duration
  setTimeout(() => {
    toast.classList.remove('opacity-100');
    toast.classList.add('opacity-0');
    setTimeout(() => toast.remove(), 300);
  }, duration);
}
