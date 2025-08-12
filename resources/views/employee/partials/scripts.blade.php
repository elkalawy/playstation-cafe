<script>
    function sessionManager() {
        return {
            startModalOpen: false,
            switchModalOpen: false,
            alertModalOpen: false,
            gameModalOpen: false,
            pricingModalOpen: false,
            modalDeviceId: null,
            modalDeviceName: '',
            modalSessionId: null,
            alertSessionId: null,
            modalGames: [],
            modalPricings: [],
            modalDeviceNameForGames: '',
            modalGamesList: [],
            modalDeviceNameForPricing: '',
            modalPricingsList: [], 
            modalGamesForPricing: [],
            selectedGameId: '', // <-- متغير جديد
            selectedGamePricings: [], // <-- متغير جديد
            playType: 'time',
            switchUrlTemplate: '{{ route("employee.play_sessions.switch", ["playSession" => "ID_PLACEHOLDER"]) }}',
            alertUrlTemplate: '{{ route("employee.play_sessions.set_alert", ["playSession" => "ID_PLACEHOLDER"]) }}',
            
            openStartModal(deviceId, deviceName, games, pricings) {
                this.modalDeviceId = deviceId; this.modalDeviceName = deviceName; this.modalGames = games.filter(g => g.is_game_based_playable); this.modalPricings = pricings; this.playType = 'time'; this.startModalOpen = true;
            },
            openSwitchModal(sessionId, games, pricings) {
                this.modalSessionId = sessionId; this.modalGames = games.filter(g => g.is_game_based_playable); this.modalPricings = pricings; this.playType = 'time'; this.switchModalOpen = true;
            },
            openAlertModal(sessionId) {
                this.alertSessionId = sessionId; this.alertModalOpen = true;
            },
            openGameModal(deviceName, games) {
                this.modalDeviceNameForGames = deviceName;
                this.modalGamesList = games;
                this.gameModalOpen = true;
            },
            openPricingModal(deviceName, timePricings, games) {
                this.modalDeviceNameForPricing = deviceName;
                this.modalPricingsList = timePricings;
                this.modalGamesForPricing = games;
                this.selectedGameId = ''; // إعادة تعيين القائمة عند كل فتح
                this.selectedGamePricings = []; // إعادة تعيين الجدول عند كل فتح
                this.pricingModalOpen = true;
            },
            // ==================== بداية الدالة الجديدة ====================
            updatePricingView() {
                if (!this.selectedGameId) {
                    this.selectedGamePricings = [];
                    return;
                }
                const selectedGame = this.modalGamesForPricing.find(g => g.id == this.selectedGameId);
                this.selectedGamePricings = selectedGame ? selectedGame.pricings : [];
            }
            // ==================== نهاية الدالة الجديدة ====================
        }
    }
    
    function timer(config) {
        return {
            startTime: config.startTime ? new Date(config.startTime) : null,
            elapsedTime: '00:00:00',
            alertTime: config.alertTime ? new Date(config.alertTime) : null,
            alertCountdown: '00:00:00',
            isTimeUp: false,
            estimatedCost: 0,
            
            init() {
                this.updateTimers();
                this.calculateCost(); 
                
                setInterval(() => {
                    this.updateTimers();
                    this.calculateCost();
                }, 1000);
            },

            updateTimers() {
                if (!this.startTime) return;
                const now = new Date();
                
                const mainDiff = now - this.startTime;
                if (mainDiff >= 0) {
                    const hours = Math.floor(mainDiff / 3600000);
                    const minutes = Math.floor((mainDiff % 3600000) / 60000);
                    const seconds = Math.floor((mainDiff % 60000) / 1000);
                    this.elapsedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }

                if (this.alertTime) {
                    const alertDiff = this.alertTime - now;
                    if (alertDiff > 0) {
                        const hours = Math.floor(alertDiff / 3600000);
                        const minutes = Math.floor((alertDiff % 3600000) / 60000);
                        const seconds = Math.floor((alertDiff % 60000) / 1000);
                        this.alertCountdown = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    } else {
                        this.alertCountdown = '00:00:00';
                        this.isTimeUp = true;
                    }
                }
            },

            calculateCost() {
                let currentPeriodCost = 0;
                
                if (config.periodType === 'time' && config.pricePerHour > 0 && config.periodStartTime) {
                    const periodDiff = new Date() - new Date(config.periodStartTime);
                    const periodMinutes = periodDiff / 60000;
                    currentPeriodCost = (periodMinutes / 60) * config.pricePerHour;
                } else if (config.periodType === 'game') {
                    currentPeriodCost = config.periodCost;
                }
                
                this.estimatedCost = config.costOfPreviousPeriods + currentPeriodCost;
            },
            
            formatCurrency(value) {
                return (value > 0) ? value.toFixed(2) : '0.00';
            }
        }
    }
</script>