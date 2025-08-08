<script>
    function sessionManager() {
        return {
            startModalOpen: false,
            switchModalOpen: false,
            modalDeviceId: null,
            modalDeviceName: '',
            modalSessionId: null,
            modalGames: [],
            modalPricings: [],
            playType: 'time',
            timeType: 'open', // المتغير الجديد للتحكم في نوع الوقت

            openStartModal(deviceId, deviceName, games, pricings) {
                this.modalDeviceId = deviceId;
                this.modalDeviceName = deviceName;
                this.modalGames = games.filter(g => g.is_game_based_playable);
                this.modalPricings = pricings;
                this.playType = 'time';
                this.timeType = 'open'; // إعادة التعيين عند فتح المودال
                this.startModalOpen = true;
            },
            
            openSwitchModal(sessionId, games, pricings) {
                this.modalSessionId = sessionId;
                this.modalGames = games.filter(g => g.is_game_based_playable);
                this.modalPricings = pricings;
                this.playType = 'time';
                this.switchModalOpen = true;
            }
        }
    }
    
    function timer(startTime, totalCost, currentPeriodCost, currentPeriodType, pricePerHour, currentPeriodStartTime) {
        return {
            startTime: startTime,
            totalCost: parseFloat(totalCost) || 0,
            currentPeriodCost: parseFloat(currentPeriodCost) || 0,
            currentPeriodType: currentPeriodType,
            pricePerHour: parseFloat(pricePerHour) || 0,
            currentPeriodStartTime: currentPeriodStartTime,
            elapsedTime: '00:00:00',
            estimatedCost: 0,
            timerInterval: null,

            startTimer() {
                this.updateDisplay();
                this.timerInterval = setInterval(() => {
                    this.updateDisplay();
                }, 1000);
            },

            updateDisplay() {
                const now = new Date();
                const diff = now - this.startTime;
                if (diff < 0) return;
                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                this.elapsedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                
                let currentDynamicCost = 0;
                if (this.currentPeriodType === 'time' && this.pricePerHour > 0 && this.totalCost === 0) { // Check if it's open time
                    const periodDiff = now - this.currentPeriodStartTime;
                    const periodMinutes = periodDiff / 60000;
                    currentDynamicCost = (periodMinutes / 60) * this.pricePerHour;
                    this.estimatedCost = this.totalCost - this.currentPeriodCost + currentDynamicCost;
                } else {
                    this.estimatedCost = this.totalCost;
                }
            },
            
            formatCurrency(value) { return value.toFixed(2); }
        }
    }
</script>