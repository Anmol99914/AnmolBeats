(function() {
    var playerBeats = window.playerBeatsData || [];
    var currentIndex = 0;
    var currentAudio = null;
    var isPlaying = false;
    
    var playerDiv = document.getElementById('floatingPlayer');
    var playPauseBtn = document.getElementById('playPauseBtn');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var trackTitleElem = document.getElementById('trackTitle');
    var trackCategoryElem = document.getElementById('trackCategory');
    var addToCartBtn = document.getElementById('addToCartPlayer');
    var progressFillDiv = document.getElementById('progressFill');
    var currentTimeSpan = document.getElementById('currentTime');
    var durationSpan = document.getElementById('duration');
    var progressBarDiv = document.querySelector('.progress-bar');
    
    function loadTrack(index) {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        
        var beat = playerBeats[index];
        if (!beat) return;
        
        trackTitleElem.textContent = beat.title;
        trackCategoryElem.textContent = beat.category.name;
        addToCartBtn.setAttribute('data-beat-id', beat.id);
        
        var audioUrl = '/storage/' + beat.audio_file;
        currentAudio = new Audio(audioUrl);
        addToCartBtn.innerHTML = 'Add to Cart $' + parseFloat(beat.price).toFixed(2);
        
        currentAudio.addEventListener('timeupdate', updateProgress);
        currentAudio.addEventListener('loadedmetadata', function() {
            durationSpan.textContent = formatTime(currentAudio.duration);
        });
        currentAudio.addEventListener('ended', function() {
            nextTrack();
        });
        
        if (isPlaying) {
            currentAudio.play();
            playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    }
    
    function togglePlay() {
        if (!currentAudio) return;
        
        if (isPlaying) {
            currentAudio.pause();
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        } else {
            currentAudio.play();
            playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        }
        isPlaying = !isPlaying;
    }
    
    function nextTrack() {
        currentIndex = (currentIndex + 1) % playerBeats.length;
        loadTrack(currentIndex);
    }
    
    function prevTrack() {
        currentIndex = (currentIndex - 1 + playerBeats.length) % playerBeats.length;
        loadTrack(currentIndex);
    }
    
    function updateProgress() {
        if (currentAudio && currentAudio.duration) {
            var percent = (currentAudio.currentTime / currentAudio.duration) * 100;
            progressFillDiv.style.width = percent + '%';
            currentTimeSpan.textContent = formatTime(currentAudio.currentTime);
        }
    }
    
    function formatTime(seconds) {
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }
    
    if (progressBarDiv) {
        progressBarDiv.addEventListener('click', function(e) {
            if (currentAudio && currentAudio.duration) {
                var rect = progressBarDiv.getBoundingClientRect();
                var percent = (e.clientX - rect.left) / rect.width;
                currentAudio.currentTime = percent * currentAudio.duration;
            }
        });
    }
    
    if (playPauseBtn) playPauseBtn.addEventListener('click', togglePlay);
    if (nextBtn) nextBtn.addEventListener('click', nextTrack);
    if (prevBtn) prevBtn.addEventListener('click', prevTrack);
    
    // Fixed Add to Cart - redirects to login if not logged in
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            var beatId = this.getAttribute('data-beat-id');
            if (beatId) {
                // Check if user is logged in by trying to fetch
                fetch('/check-auth')
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data.logged_in) {
                            // Logged in - add to cart
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/cart/add/' + beatId;
                            var token = document.querySelector('meta[name="csrf-token"]').content;
                            form.innerHTML = '<input type="hidden" name="_token" value="' + token + '">';
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // Not logged in - go to login
                            window.location.href = '/login';
                        }
                    })
                    .catch(function() {
                        window.location.href = '/login';
                    });
            }
        });
    }
    
    window.showPlayer = function() {
        if (playerDiv) {
            playerDiv.classList.add('show');
            if (playerBeats.length > 0 && !currentAudio) {
                currentIndex = 0;
                loadTrack(0);
            }
        }
    };
    
    window.hidePlayer = function() {
        if (playerDiv) {
            playerDiv.classList.remove('show');
            if (currentAudio) {
                currentAudio.pause();
                isPlaying = false;
            }
        }
    };
    
    if (playerBeats.length > 0) {
        loadTrack(0);
        if (currentAudio) {
            currentAudio.pause();
            isPlaying = false;
        }
    }
})();