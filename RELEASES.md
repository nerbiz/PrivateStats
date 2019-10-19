# Release notes

## 0.3.0
#### 2019-10-19

* Added fromArray(), fromStdClass(), toArray() and toStdClass() methods to VisitInfo.
* Moved read query logic to the new ReadQuery class.
* Added 'order by' functionality for reading visit information.
* WhereClause now supports 'like' comparison.
* The write/read methods of PrivateStats are now more intuitive.
* Renamed the Drivers namespace to DatabaseDrivers.
* Removed the 'date' property of stored visit information, it's redundant because of 'timestamp'.

## 0.2.0
#### 2019-10-17

* Added a wildcard option to excluding IP addresses.
* Added reading of a CSV file.
* Added reading of an XML file.
* Added reading of a database.

## 0.1.1
#### 2019-10-14

* Updated the license name, because of a Packagist crawl error.

## 0.1.0
#### 2019-10-13

* Added possibility to store visit stats to an XML file.
* Added possibility to store visit stats to a CSV file.
* Added possibility to store visit stats to a MySQL database.
* Hashing IP addresses for anonymity.
