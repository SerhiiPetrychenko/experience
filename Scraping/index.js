const browserObject = require('./browser');
const scraperControllerCategories = require('./categories/pageController');

//Start the browser and create a browser instance
let browserInstance = browserObject.startBrowser();

// Pass the browser instance to the scraper controller
scraperControllerCategories(browserInstance)
