videoDR
=======

This PHP script is used to get data from mysql and save them into csv files based on different satations. 

The data is updated once per day in mysql, so the data retrieval program is set to read data every 24 hours. 

Based on the csv files, further program written in C# is used to process the data. Aggregate the 1 min data into 15 mins data and save them into sql server. 
