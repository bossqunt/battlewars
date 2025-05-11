const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8080 });

// Store online players
const onlinePlayers = new Map();

wss.on('connection', (ws) => {
    console.log('A player connected');

    // Handle player registration
    ws.on('message', (message) => {
        const data = JSON.parse(message);
        if (data.type === 'register') {
            onlinePlayers.set(data.playerId, ws);
            broadcastOnlinePlayers();
        }
    });

    // Handle disconnection
    ws.on('close', () => {
        for (const [playerId, socket] of onlinePlayers.entries()) {
            if (socket === ws) {
                onlinePlayers.delete(playerId);
                break;
            }
        }
        broadcastOnlinePlayers();
    });
});

function broadcastOnlinePlayers() {
    const playerList = Array.from(onlinePlayers.keys());
    const message = JSON.stringify({ type: 'onlinePlayers', players: playerList });

    for (const ws of onlinePlayers.values()) {
        ws.send(message);
    }
}

console.log('WebSocket server is running on ws://localhost:8080');