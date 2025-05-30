document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.querySelector('.sidebar');
  const toggleSidebarButton = document.getElementById('toggleSidebar');
  const closeSidebarButton = document.getElementById('closeSidebar');

  toggleSidebarButton.addEventListener('click', function() {
    sidebar.classList.toggle('active');
  });

  closeSidebarButton.addEventListener('click', function() {
    sidebar.classList.remove('active');
  });

  // Hide close button on large screens initially
  if (window.innerWidth > 800) {
    closeSidebarButton.style.display = 'none';
  }

  // Event listener to check window width and toggle close button visibility
  window.addEventListener('resize', function() {
    if (window.innerWidth > 800) {
      closeSidebarButton.style.display = 'none';
    } else {
      closeSidebarButton.style.display = 'block';
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const invoiceBtn = document.getElementById('invoiceBtn');
  const invoiceModal = document.getElementById('invoiceModal');
  const closeInvoice = document.getElementById('closeInvoice');

  if (invoiceBtn && invoiceModal && closeInvoice) {
    // Mở popup khi nhấn nút
    invoiceBtn.addEventListener('click', () => {
      invoiceModal.style.display = 'block';
    });

    // Đóng popup khi nhấn nút đóng
    closeInvoice.addEventListener('click', () => {
      invoiceModal.style.display = 'none';
    });

    // Đóng popup khi nhấn ngoài vùng modal
    window.addEventListener('click', (e) => {
      if (e.target === invoiceModal) {
        invoiceModal.style.display = 'none';
      }
    });
  }

});

