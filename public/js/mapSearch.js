let cat1 = document.getElementById("searchCat1");
let cat2 = document.getElementById("searchCat2");
let cat3 = document.getElementById("searchCat3");
let cat4 = document.getElementById("searchCat4");
let searchBar = document.getElementById("searchBar");

cat1.addEventListener("click", function () {
    searchBar.value = 'Cat1';
});
cat2.addEventListener("click", function () {
    searchBar.value = 'Cat2';
});
cat3.addEventListener("click", function () {
    searchBar.value = 'Cat3';
});
cat4.addEventListener("click", function () {
    searchBar.value = 'Cat4';
});



