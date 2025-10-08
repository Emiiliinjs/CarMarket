export default function liveBid({ pollUrl, storeUrl, currentBid, nextBidAmount, minIncrement, recentBids }) {
    return {
        currentBid: currentBid,
        nextBidAmount: nextBidAmount,
        minIncrement: minIncrement,
        bids: recentBids || [],
        error: null,
        loading: false,

        async poll() {
            try {
                const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Server error');
                const data = await res.json();

                this.currentBid = data.currentBid;
                this.nextBidAmount = data.nextBidAmount;
                this.minIncrement = data.minIncrement;
                this.bids = data.bids;
            } catch (err) {
                console.error('Polling error:', err);
            }
        },

        async placeBid() {
            this.loading = true;
            this.error = null;

            try {
                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ amount: this.nextBidAmount })
                });

                if (!res.ok) {
                    const errorData = await res.json();
                    throw new Error(errorData.message || 'Bid failed');
                }

                const data = await res.json();
                this.currentBid = data.currentBid;
                this.nextBidAmount = data.nextBidAmount;
                this.bids = data.bids;
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },

        increase() {
            this.nextBidAmount = parseFloat(this.nextBidAmount) + this.minIncrement;
        },

        decrease() {
            if (this.nextBidAmount > this.currentBid + this.minIncrement) {
                this.nextBidAmount = parseFloat(this.nextBidAmount) - this.minIncrement;
            }
        }
    };
}
