## Shipments Discount App

In a real world scenario I'd would have studied first the requirements, ask questions, break down the story, ask more questions if needed, and then once everything is crystal clear I'd start coding.  

Of course, giving and receiving feedback doesn't stop there, as it is an important part embedded in the whole lifecycle of a story. 

But this time,  working on and off whenever I get some time without the kids interrupting me, I didn’t manage the process in the best possible way.

First, I implemented a service to read the input file with an iterator, with the idea to go reading and processing line by line, in order not to load all the rows in memory.  But I should have started implementing the rules.  Because that assumption doesn’t work for the rules that need to keep some state.  That's why now there are many foreachs.

So I had to break the rules into the ones that can process the shipment orders row by row, like the medium shipment, and the ones that need all the rows, like the small and large shipments and the accumulated discounts.

Also, another wrong assumption I'll have to live with is the price and discount calculation for the small shipments.

Here is the process that I followed:
- Read a text file, parse it and return an iterator & test √
- Discount rules & tests √
- Change discount rules to rules per shipment or rules that needed some state √
- Discount calculator √
- Input validator (/Users/radt/Sites/old-coding-challenge-wholesaler-integration/src/kollex/Dataprovider/Assortment/Product.php) √
- Console command
- Dockerize
- Clean the framework from unnecesary files