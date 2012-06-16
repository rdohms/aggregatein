Aggregate.in - The Joind.in Talk Aggregator
===========================================

This application has a very simple objective: Give you a summary page that aggregates all the times you gave a specific talk. For example: if you gave you talk "PHP 101" in 5 different events, you can now combine those 5 events into a nice looking page that gives you an overview of the rating for each instance of it, an overall score and a nice little evolution graph. This is great for Call for Paper submissions.

The secondary objectives for making this are also simple: Play around with the new Azure tools, like website and git integrations, as well as their new PHP SDK available through Composer. It was also a chance to play around with the Joind.in API.

The site is available at: http://aggregatein.azurewebsites.net/

Tools Used
==========

* Silex
* Azure PHP SDK
* Azure Table Storage
* Azure Website with Git deploys
* WinCache (pending)

Contributing
============

If you want to contribute, checkout the code and run:

    composer.phar install

You may have issues with includes in the Azure SDK, for now use a search and replace to remove them, they are looking into it.