// page navigation
const dashboard = document.querySelector(".main");
const items = document.querySelector(".items-main");
const claimHistory = document.querySelector(".claim-history-main");

const dashboardButton = document.querySelector(".dashboard");
const itemsButton = document.querySelector(".items");
const claimHistoryButton = document.querySelector(".claim-history");

function dashboardNone() {
  dashboard.style.display = "none";
}

function itemsNone() {
  items.style.display = "none";
}

function claimHistoryNone() {
  claimHistory.style.display = "none";
}

dashboardButton.addEventListener("click", () => {
  itemsNone();
  claimHistoryNone();
  dashboard.style.display = "flex";
});

itemsButton.addEventListener("click", () => {
  dashboardNone();
  claimHistoryNone();
  items.style.display = "flex";
});

claimHistoryButton.addEventListener("click", () => {
  dashboardNone();
  itemsNone();
  claimHistory.style.display = "flex";
});

// toggling the lost item form
const lostItemForm = document.querySelector(".new-lost-item-form");
const addItemButton = document.querySelector(".add-item");
const closeFormButton = document.querySelector(".close-form-button");

addItemButton.addEventListener("click", () => {
  lostItemForm.style.display = "flex";
});

closeFormButton.addEventListener("click", (e) => {
  e.preventDefault();
  lostItemForm.style.display = "none";
});
