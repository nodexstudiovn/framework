/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * NodeX Realtime WebSocket Server (Micro-Reverb Engine)
 */

const { WebSocketServer } = require('ws');
const net = require('net');

const PORT = 6001;
const REDIS_HOST = process.env.REDIS_HOST || '127.0.0.1';
const REDIS_PORT = parseInt(process.env.REDIS_PORT || '6379', 10);
const CHANNEL = 'chat-channel';

const wss = new WebSocketServer({ port: PORT });
const clients = new Set();

console.log(`\x1b[36m⚡ NodeX Realtime WebSocket Server starting on ws://localhost:${PORT}\x1b[0m`);

wss.on('connection', (ws) => {
    clients.add(ws);
    console.log(`\x1b[32m[CONNECTED]\x1b[0m Client connected. Total active clients: ${clients.size}`);

    ws.on('close', () => {
        clients.delete(ws);
        console.log(`\x1b[31m[DISCONNECTED]\x1b[0m Client disconnected. Total active clients: ${clients.size}`);
    });

    ws.on('error', (err) => {
        console.error('\x1b[31m[WS ERROR]\x1b[0m', err.message);
        clients.delete(ws);
    });
});

/**
 * Thử kết nối ioredis nếu sẵn có, nếu không tự động fallback sang Socket TCP Redis Subscriber
 */
function initRedisSubscriber() {
    try {
        const Redis = require('ioredis');
        const redisSub = new Redis({ host: REDIS_HOST, port: REDIS_PORT, retryStrategy: () => 3000 });

        redisSub.subscribe(CHANNEL, (err, count) => {
            if (err) {
                console.error('\x1b[31m[REDIS ERROR]\x1b[0m Thất bại khi đăng ký channel:', err.message);
            } else {
                console.log(`\x1b[32m[SUBSCRIBED]\x1b[0m Đã đăng ký lắng nghe kênh [${CHANNEL}] từ PHP Server.`);
            }
        });

        redisSub.on('message', (channel, message) => {
            if (channel === CHANNEL) {
                broadcast(message);
            }
        });
    } catch (e) {
        console.log('\x1b[33m[NOTICE]\x1b[0m Đang sử dụng Socket TCP Native để kết nối Redis Sub/Pub...');
        initNativeSocketSubscriber();
    }
}

function initNativeSocketSubscriber() {
    const client = net.createConnection({ host: REDIS_HOST, port: REDIS_PORT }, () => {
        console.log(`\x1b[32m[NATIVE REDIS]\x1b[0m Đã kết nối TCP Redis ${REDIS_HOST}:${REDIS_PORT}`);
        client.write(`*2\r\n$9\r\nSUBSCRIBE\r\n$${CHANNEL.length}\r\n${CHANNEL}\r\n`);
    });

    client.on('data', (data) => {
        const str = data.toString('utf-8');
        if (str.includes(CHANNEL)) {
            const lines = str.split('\r\n');
            const message = lines[lines.length - 2];
            if (message && message.startsWith('{')) {
                broadcast(message);
            }
        }
    });

    client.on('error', (err) => {
        console.log('\x1b[31m[NATIVE REDIS ERROR]\x1b[0m', err.message);
        setTimeout(initNativeSocketSubscriber, 5000);
    });
}

function broadcast(messageStr) {
    console.log(`\x1b[35m[BROADCAST]\x1b[0m Phát tin nhắn tới ${clients.size} client(s):`, messageStr);
    for (const client of clients) {
        if (client.readyState === 1) { // 1 = OPEN
            client.send(messageStr);
        }
    }
}

initRedisSubscriber();
