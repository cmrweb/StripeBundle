import { Controller } from '@hotwired/stimulus';
import { loadStripe } from '@stripe/stripe-js';

export default class extends Controller {
    static values = {
        publishableKey: String,     
        clientSecretUrl: String
    }

    connect() {
        this.initialize();
    }

    async initialize() {
        const stripe = await loadStripe(this.publishableKeyValue);
        const fetchClientSecret = async () => {
            const response = await fetch(this.clientSecretUrlValue, {
                method: "POST",
            });
            const { clientSecret } = await response.json();
            return clientSecret;
        };
        const checkout = await stripe.initEmbeddedCheckout({
            fetchClientSecret,
        });
        checkout.mount('#checkout');
    }
}
