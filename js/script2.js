const mysql = require('mysql');

// MySQLデータベースに接続
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'your_username',
    password: 'your_password',
    database: 'your_database'
});

// 薬別の平均効果持続時間を取得するクエリ
const query = `
  SELECT
    medicine_name,
    AVG(effect_duration) AS average_duration
  FROM
    medicine_effects
  GROUP BY
    medicine_name;
`;

// クエリを実行
connection.query(query, (error, results) => {
    if (error) {
        console.error('Error executing query:', error);
        return;
    }

    // 結果を表示
    console.log('Average effect durations by medicine:');
    results.forEach(row => {
        console.log(`${row.medicine_name}: ${row.average_duration}`);
    });

    // 接続を終了
    connection.end();
});