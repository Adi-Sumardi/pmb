self.addEventListener("install", (event) => {
  console.log("Service Worker Installed");
});

// Removed empty fetch event handler to prevent no-op warning
// Add fetch handler only when implementing actual caching strategy
