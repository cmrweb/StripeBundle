<div {{ attributes.defaults(stimulus_controller('cmrweb/stripe-bundle/stripe', {
        publishableKey: stripe_public,
        clientSecretUrl: path('app_checkout')
    })) }}  >
    <div id="checkout"></div>
</div>
