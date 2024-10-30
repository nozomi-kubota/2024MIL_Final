// アクセストークンを設定します
const accessToken = 'RZMOA7OPNJUV3MSHADRJU5YAB65C3ARG';

// APIエンドポイント
const apiUrl = 'https://api.ouraring.com/v2/usercollection/sleep';

// リクエストオプション
const requestOptions = {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json'
    }
};

// データを取得する関数
async function fetchSleepData() {
    try {
        const response = await fetch(apiUrl, requestOptions);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Sleep Data:', data);
    } catch (error) {
        console.error('Error fetching sleep data:', error);
    }
}

// 関数を呼び出してデータを取得します
fetchSleepData();