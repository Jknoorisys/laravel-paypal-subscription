<h1>Laravel Paypal Subscription</h1>

<h4>Steps to clone laravel project</h4>
<ul>
    <li>Clone your project</li>
    <li>Go to the folder application using cd command on your cmd or terminal</li>
    <li>Run composer install on your cmd or terminal</li>
    <li>Copy .env.example file to .env in the root folder using command <i> cp .env.example .env </i></li>
    <li>Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.</li>
    <li>Run php artisan key:generate</li>
    <li>Run php artisan migrate</li>
    <li>Run php artisan serve</li>
    <li>Go to http://localhost:8000/</li>
</ul>

<h4>Follow the steps below:</h4>
<ul>
    <li> Create New Project </li>
    <li> Install Packages for Paypal Payment Gateway Using Composer </li>
    <li> Create PayPal account </li>
    <li> Configure the package </li>
    <li> Create Product and Subscription Plan</li>
    <li> Create Routes </li>
    <li> Run the app </li>
    <li> Run APIs on Postman (https://api.postman.com/collections/18476697-7e81ea1c-7d1e-4965-9953-b25b40ebaf25?access_key=PMAT-01GVFRSKHNB4F5RY2CWY4G75YG) </li>   
</ul>

<ol>
    <li><h5>Create a new project</h5></li>
        <p>Create a new project with the command as below.</p>
        <p><i>composer create-project laravel/laravel-paypal-subscription</i></p>
        <p>After the new project has been created, go to your project directory.</p>
        <p><i>cd laravel-paypal-subscription</i></p>
    <li><h5>Install Packages for Paypal Payment Gateway Using Composer</h5></li>
        <p>Run the following command.</p>
        <p><i>composer require srmklive/paypal</i></p>
    <li><h5>Create PayPal Account</h5></li>
        <p>After installing paypal package, we need client_id and secret_key for paypal integration, so we need to enter in paypal developer mode and create new sandbox account for the same. After login in paypal you need to get the client_id and secret_key as shown below. before getting client_id and secret_key we need to create application. So, check the screenshot below and build an app. Login to the Developer Dashboard.</p>
        <p>Step 1: Login to https://developer.paypal.com/</p>
        <p>Step 2: Navigate to sandbox -> Account -> Create Account (https://developer.paypal.com/developer/accounts/)</p>
        <p>the company and personal account of your working sandbox.Step 2: Navigate to sandbox -> Account -> Create Account (https://developer.paypal.com/developer/accounts/)</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*QPksWVvy8dU6DnF3UdNAFg.png" alt="img" >
        <p>Click create account to create the sandbox account.</p>
        <p>Step 3: Select the business account and Country/Region of business.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*ZJ7s0kVqQb1yNU1HShq3-w.png" alt="img" >
        <p>Now, your business account is created.</p>
        <p>Step 4: Once the account is created, you can get the client key and secret key that will be used in the application.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/0*s0MdGxcaTC4mo2ys" alt="img">    
    <li><h5>Configure the package</h5></li>
        <p>After the package installation is complete, you open your project and add the key and secret key that you got in the .env file.</p>
        <p>
        PAYPAL_MODE=sandbox<br>
        PAYPAL_SANDBOX_CLIENT_ID=AXELAz06GFLR.............................QNu7zyjuYpFLu1g<br>
        PAYPAL_SANDBOX_CLIENT_SECRET=EA9dinW1.............................PUzgVQCz7fK4tqe1-jLZCyHzZ0tDTRAx-6qJdIY933Q
        </p>
        <p>If you want to customize the package’s default configuration options, run the vendor:publish command below.</p>
        <p><i>php artisan vendor:publish --provider "Srmklive\PayPal\Providers\PayPalServiceProvider"</i></p>
        <img src="https://miro.medium.com/v2/resize:fit:640/0*78fimJBrscB_gjQx" alt="img">
        <p>This will create a configuration file config/paypal.php with the details below, which you can modify.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/0*KZjimfTUs0el7ZWL" alt="img">
    <li><h5>Set up the Subscription plan</h5></li>
        <p>Step 1: Create Product:</p>
        <p>First, we have to create the product from the PayPal dashboard. Login to https://www.sandbox.paypal.com/ using the business account credentials created above.</p>
        <p>Navigate to Pay & get paid -> Subscriptions -> Subscription plans -> Create Plan.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*D21dp0H2-ucPE6mUO8j6Hg.png" alt="img" >
        <p>If you have any existing product, it will be listed over there(for example silver). You can click on Create a subscription product and follow the steps to create a product for which you wanna ask your users to subscribe. Fill the product-specific details.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*oGHObaBXuuOxel9wOBHd8A.png" alt="img" >
        <p>You can enter your Product Name, Product description, Product ID, Product Type, Industry Category. Product Page URL is the place where your customer will find your subscription product on the website. Next is the product image URL. Here, Product name and Product Category is only required. Rest all the fields are optional. After creating the product, you have to choose the product and create a plan.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*tHtYxOUxqBqBjRwQB26l7A.png" alt="img" >
        <p>Choose the product which you wanna offer and click next to create the plan.</p>
        <p>Step 2: Create Plan:</p>
        <p>After creating the product, you need to create a plan which will specify the duration on which the transaction will happen and the money will be automatically deducted from the customer PayPal account.</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*CQusiLIlTKm1v-RwRvpNBg.png" alt="img" >
        <p>You have to select what kind of scheme you wanna offer(Fixed pricing/Quantity pricing). Fixed pricing is where your price is fixed and at the beginning of the next billing cycle, a fixed amount will be deducted from the customer’s account. Quantity Pricing is where the price is calculated based on the quantity of the product being purchased.</p>
         <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*murpBabV_tBt1FCZ1upmsg.png" alt="img">
         <p>Now, you have to give the plan name(monthly, quarterly, yearly, and so on). Also, give the plan description. This will make customers understand the duration on which the money will be deducted from their account, once subscribed.</p>
         <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*GTnZxSigGGoq3MjGbDeHUg.png" alt="img">
         <p>Next, you specify the price of your product and the billing cycle. Here, you configure the duration on which the price is deducted, tax calculation, missed billing cycle, and so on. Again, this is a total business decision, how the transaction will happen. After specifying all the details, click next.</p>
        <p>Step 3: Review and Save</p>
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/1*noLmXd6MlqY_DvaT8cQRRg.png" alt="img" >
        <p>Now, your product and plan are created. The next step is turning your plan on and implementing it in the code.</p>
    <li><h5>Create Routes</h5></li>
        <p>Now we need to create an application route that we will test the application test transaction on. Open the route/api.php application route file and add the new routes</p>
        <p>Create Controller</p>
        <p><i>php artisan make:controller api/PayPalController</i></p>
        <p>Run database migrations</p>
        <p><i>php artisan migrate</i></p>
        <p>Add/Update plan details in database</p>
    <li><h5>Run the app</h5></li>
        <p>Paypal integration complete. Now we need to make a transaction. Run the Laravel server using the Artisan command below.</p>
        <p><i>php artisan serve</i></p>
    <p>Thus this tutorial I provide, hopefully useful.</p>
    <p>Thanks.</p>  
</ol>
