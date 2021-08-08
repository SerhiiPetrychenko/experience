const pageScraper = require('./pageScraper');
async function scrapeAll(browserInstance){
    let browser;
    try{
        browser = await browserInstance;

        let scrapedData = await pageScraper.scraper(browser);

        await browser.close();
         console.log(JSON.stringify(scrapedData));
    }
    catch(err){
        console.log("ERROR => ", err);
    }
}

module.exports = (browserInstance) => scrapeAll(browserInstance)
