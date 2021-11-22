# Cyber-A*cti*VE
The Cyber-Attack Cyber Threat Intelligence Virtual Enviroment (Cyber-A**cti**VE) is a visual front-end to STIX&trade; models. The tool is integrated with [STIX's specification](https://docs.oasis-open.org/cti/stix/v2.1/os/stix-v2.1-os.html) provided by [OASIS Open](https://www.oasis-open.org/).
A functional version of the tools is available at [this link](https://cyberactive.performanceware.com.br/).

# Funding
This research is funded by the Industrial Strategy Challenge Fund and EPSRC, [EP/V012053/1](https://gow.epsrc.ukri.org/NGBOViewGrant.aspx?GrantRef=EP/V012053/1), through the [Active Building Centre - Research Programme (ABC RP)](https://abc-rp.com/).

# Technical details
The tool is coded in PHP (PHP Hypertext Pre-Processor) programming language (three-layer application), to run in Apache (or any other application server), and with the help of a Database Management System (DBMS).
It uses two JSON files with the STIX&trade; specification for the parameters and required data types:
1. [STIX2.1.json](https://github.com/czekster/cyberactive/blob/main/json/STIX2.1.json), created based on STIX&trade; specification
2. [STIX2.1-vocabularies.json](https://github.com/czekster/cyberactive/blob/main/json/STIX2.1-vocabularies.json), also created based on STIX&trade; documentation on vocabularies, types, and identifiers.
  
# Next steps
- For this application you will require: PHP7+ , MySQL , Apache
  - optionally, you may want to install [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)
  - for MS-Windows, one could also employ [WAMP](https://www.wampserver.com/en/)
- Rename `Properties-sample.php` to `Properties.php`
  - change to your settings
  - ALL properties must be set, for DBMS connection and for sendmail (to allow password retrieval)
- Create a database (the suggested DBMS is MySQL, you may use what you want)
  - set a schema (database name)
  - dump tables into DBMS: look at file `database-dump/db_dump.sql`
  - update DBMS configuration to `Properties.php`

# Researchers involved
- Ricardo M. Czekster, Research Associate, School of Computing, Newcastle University
- Roberto Metere, Research Associate, School of Computing, Newcastle University and The Alan Turing Institute, London
- Charles Morisset, Senior Lecturer, School of Computing, Newcastle University

