Omeka-CronCleaner
=================

Plugin OMEKA 2, add cron for cleaning "vocab"

Work done as part of HRC-funded project "Visualising European Crime Fiction: New Digital Tools and Approaches to the Study of Transnational Popular Culture", PI: Dr. Dominique Jeannerod (School of Modern Languages, Queen's University Belfast)

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.

Install
-------
Be careful of specification, open the 2 files in your text editor to manage the modifications
db-connexion : to write your data base specifications
croncleaner : to see the specifications of regexp in order to clean the field(s)

This plugin do follow routes from Omeka, it needs a cron task to be setup in order to work.
ex : * 11-12 * * * php -f /your_path_to_omeka2/plugins/CronCleaner/croncleaner.php

