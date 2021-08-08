const scraperObject = {
    url: 'http://books.toscrape.com',
    async scraper(browser){
        let page = await browser.newPage();
        console.log(`Navigating to ${this.url}...`);
        await page.goto(this.url);
        // Wait for the required DOM to be rendered
        await page.waitForSelector('.page_inner');
        //Get a list of categories
        let dataObj = await page.$$eval('.nav-list > li > ul > li', elements => {
            //Get the text from the "a" tag and remove the extra newlines, tabs, and spaces at the beginning and end of the line.
            elements = elements.map(a => a.textContent.replace(/(\r\n\t|\n|\r|\t)/gm, "").trim());

            return elements;
        });

        return dataObj;
    }
}

module.exports = scraperObject;
