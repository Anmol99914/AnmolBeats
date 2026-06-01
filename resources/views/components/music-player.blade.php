@php
    $featuredBeats = App\Models\Beat::with('category')->inRandomOrder()->limit(10)->get();
@endphp

<!-- Pass data through meta tag -->
<meta id="playerBeatsData" content="{{ base64_encode(json_encode($featuredBeats)) }}">

<div class="floating-player" id="floatingPlayer">
    <div class="player-container">
        <div class="player-controls">
            <button class="player-btn" id="prevBtn">
                <i class="fas fa-step-backward"></i>
            </button>
            <button class="player-btn play-pause" id="playPauseBtn">
                <i class="fas fa-play"></i>
            </button>
            <button class="player-btn" id="nextBtn">
                <i class="fas fa-step-forward"></i>
            </button>
        </div>
        
        <div class="player-info">
            <div class="track-info">
                <h6 id="trackTitle">Select a beat</h6>
                <p id="trackCategory" class="text-muted small">-</p>
            </div>
            <div class="track-price">
                <button class="btn btn-sm btn-primary" id="addToCartPlayer">Add to Cart</button>
            </div>
        </div>
        
        <div class="player-progress">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="time-info">
                <span id="currentTime">0:00</span>
                <span id="duration">0:00</span>
            </div>
        </div>
    </div>
</div>

<style>
    .floating-player {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(10px);
        border-top: 1px solid #e63946;
        padding: 15px 20px;
        z-index: 1000;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 -5px 30px rgba(0, 0, 0, 0.5);
    }
    
    .floating-player.show {
        transform: translateY(0);
    }
    
    .player-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .player-controls {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .player-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .player-btn:hover {
        color: #e63946;
        transform: scale(1.1);
    }
    
    .play-pause {
        background: #e63946;
        color: white;
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .play-pause:hover {
        background: #c1121f;
        transform: scale(1.05);
        color: white;
    }
    
    .player-info {
        flex: 1;
        min-width: 200px;
    }
    
    .track-info h6 {
        margin: 0;
        color: white;
    }
    
    .track-info p {
        margin: 0;
        font-size: 12px;
    }
    
    .player-progress {
        min-width: 300px;
        flex: 2;
    }
    
    .progress-bar {
        background: #333;
        height: 4px;
        border-radius: 2px;
        cursor: pointer;
        position: relative;
    }
    
    .progress-fill {
        background: #e63946;
        height: 100%;
        border-radius: 2px;
        width: 0%;
        transition: width 0.1s linear;
    }
    
    .time-info {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        font-size: 12px;
        color: #aaa;
    }
    
    .show-player-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #e63946;
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 999;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s;
        border: none;
    }
    
    .show-player-btn:hover {
        transform: scale(1.1);
        background: #c1121f;
    }
    
    .show-player-btn i {
        font-size: 24px;
    }
    
    audio::-webkit-media-controls-download-button {
        display: none !important;
    }
    
    audio::-webkit-media-controls-enclosure {
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .player-container {
            flex-direction: column;
        }
        .player-progress {
            width: 100%;
        }
        .track-price {
            margin-top: 10px;
        }
    }
</style>