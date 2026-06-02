// Floating Player JavaScript
(function() {
    var playerBeatsData = document.getElementById('player-beats-data');
    if (playerBeatsData) {
        window.playerBeats = JSON.parse(atob(playerBeatsData.value));
    } else {
        window.playerBeats = [];
    }
    
    window.currentAudio = null;
    window.isPlaying = false;
    window.currentBeatId = null;
    
    window.formatTime = function(seconds) {
        if (isNaN(seconds)) return '0:00';
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    };
    
    window.updateProgress = function() {
        if (window.currentAudio && window.currentAudio.duration) {
            var percent = (window.currentAudio.currentTime / window.currentAudio.duration) * 100;
            var progressFill = document.getElementById('progressFill');
            var currentTimeSpan = document.getElementById('currentTime');
            if (progressFill) progressFill.style.width = percent + '%';
            if (currentTimeSpan) currentTimeSpan.textContent = window.formatTime(window.currentAudio.currentTime);
        }
    };
    
    window.showLoading = function(show) {
        var playPauseBtn = document.getElementById('playPauseBtn');
        if (show) {
            if (playPauseBtn) playPauseBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        } else {
            if (playPauseBtn && window.isPlaying) {
                playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            } else if (playPauseBtn) {
                playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        }
    };
    
    window.playBeat = function(beatId, beatTitle, beatCategory, beatPrice, beatAudioUrl) {
        console.log('playBeat called:', beatTitle);
        
        var playerDiv = document.getElementById('floatingPlayer');
        var trackTitle = document.getElementById('trackTitle');
        var trackCategory = document.getElementById('trackCategory');
        var addToCartBtn = document.getElementById('addToCartPlayer');
        var playPauseBtn = document.getElementById('playPauseBtn');
        var durationSpan = document.getElementById('duration');
        var progressFill = document.getElementById('progressFill');
        var currentTimeSpan = document.getElementById('currentTime');
        
        if (!playerDiv) {
            console.error('Floating player not found!');
            return;
        }
        
        // Show loading
        window.showLoading(true);
        
        // Reset UI
        if (progressFill) progressFill.style.width = '0%';
        if (currentTimeSpan) currentTimeSpan.textContent = '0:00';
        if (durationSpan) durationSpan.textContent = '0:00';
        
        // Stop current audio if playing
        if (window.currentAudio) {
            window.currentAudio.pause();
            window.currentAudio = null;
        }
        
        // Update track info
        if (trackTitle) trackTitle.textContent = beatTitle;
        if (trackCategory) trackCategory.textContent = beatCategory;
        window.currentBeatId = beatId;
        if (addToCartBtn) addToCartBtn.setAttribute('data-beat-id', beatId);
        if (addToCartBtn) addToCartBtn.innerHTML = 'Add to Cart $' + parseFloat(beatPrice).toFixed(2);
        
        // Create new audio with preload
        window.currentAudio = new Audio(beatAudioUrl);
        window.currentAudio.preload = 'auto';
        window.currentAudio.setAttribute('controlsList', 'nodownload');
        
        // Event listeners
        window.currentAudio.addEventListener('canplay', function() {
            window.showLoading(false);
            window.currentAudio.play();
            window.isPlaying = true;
            if (playPauseBtn) playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            if (durationSpan) durationSpan.textContent = window.formatTime(window.currentAudio.duration);
        });
        
        window.currentAudio.addEventListener('timeupdate', window.updateProgress);
        window.currentAudio.addEventListener('loadedmetadata', function() {
            if (durationSpan) durationSpan.textContent = window.formatTime(window.currentAudio.duration);
        });
        window.currentAudio.addEventListener('ended', function() {
            if (playPauseBtn) playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            window.isPlaying = false;
        });
        window.currentAudio.addEventListener('error', function() {
            window.showLoading(false);
            console.error('Error loading audio');
        });
        
        // Show player
        playerDiv.classList.add('show');
    };
    
    // Set up event listeners after DOM loads
    document.addEventListener('DOMContentLoaded', function() {
        var playPauseBtn = document.getElementById('playPauseBtn');
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', function() {
                if (window.currentAudio) {
                    if (window.isPlaying) {
                        window.currentAudio.pause();
                        this.innerHTML = '<i class="fas fa-play"></i>';
                        window.isPlaying = false;
                    } else {
                        window.currentAudio.play();
                        this.innerHTML = '<i class="fas fa-pause"></i>';
                        window.isPlaying = true;
                    }
                }
            });
        }
        
        var addToCartBtn = document.getElementById('addToCartPlayer');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                var beatId = window.currentBeatId;
                if (beatId) {
                    fetch('/check-auth')
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            if (data.logged_in) {
                                var form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '/cart/add/' + beatId;
                                var token = document.querySelector('meta[name="csrf-token"]').content;
                                form.innerHTML = '<input type="hidden" name="_token" value="' + token + '">';
                                document.body.appendChild(form);
                                form.submit();
                            } else {
                                window.location.href = '/login';
                            }
                        })
                        .catch(function() {
                            window.location.href = '/login';
                        });
                }
            });
        }
        
        // Progress bar seek
        var progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.addEventListener('click', function(e) {
                if (window.currentAudio && window.currentAudio.duration) {
                    var rect = this.getBoundingClientRect();
                    var percent = (e.clientX - rect.left) / rect.width;
                    window.currentAudio.currentTime = percent * window.currentAudio.duration;
                }
            });
        }
        
        // Close player when clicking outside
        document.addEventListener('click', function(event) {
            var playerDiv = document.getElementById('floatingPlayer');
            var playerBtn = document.querySelector('.show-player-btn');
            if (playerDiv && playerDiv.classList.contains('show')) {
                if (!playerDiv.contains(event.target) && event.target !== playerBtn && !playerBtn.contains(event.target)) {
                    playerDiv.classList.remove('show');
                    if (window.currentAudio) {
                        window.currentAudio.pause();
                        window.isPlaying = false;
                    }
                }
            }
        });
    });
    
    window.showPlayer = function() {
        var playerDiv = document.getElementById('floatingPlayer');
        if (playerDiv) playerDiv.classList.add('show');
    };
    
    window.hidePlayer = function() {
        var playerDiv = document.getElementById('floatingPlayer');
        if (playerDiv) playerDiv.classList.remove('show');
        if (window.currentAudio) {
            window.currentAudio.pause();
            window.isPlaying = false;
        }
    };

    // Add this function to stop all page audio elements
window.stopAllPageAudio = function() {
    var allAudio = document.querySelectorAll('.beat-audio');
    allAudio.forEach(function(audio) {
        if (!audio.paused) {
            audio.pause();
            audio.currentTime = 0;
        }
    });
};

// Update the playBeat function to stop page audio
window.playBeat = function(beatId, beatTitle, beatCategory, beatPrice, beatAudioUrl) {
    // Stop any playing audio on the page
    window.stopAllPageAudio();
    
    // Rest of your existing playBeat code...
    console.log('playBeat called:', beatTitle);
    
    var playerDiv = document.getElementById('floatingPlayer');
    // ... rest of your code
};
})();