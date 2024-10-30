const searchInput = document.getElementById('search-input');
const rows = document.querySelectorAll('tr:not(:first-child)'); // タイトル行以外のテーブルの行を取得

searchInput.addEventListener('input', function () {
    const searchTerm = searchInput.value.toLowerCase();

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = 'table-row'; // マッチする行を表示
        } else {
            row.style.display = 'none'; // マッチしない行を非表示
        }
    });
});