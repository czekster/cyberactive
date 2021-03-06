# cybera*CTI*ve
The Cyber-Attack Cyber Threat Intelligence Virtual Enviroment (cybera**CTI**ve) is a visual front-end to STIX&trade; models. The tool is integrated with [STIX's specification](https://docs.oasis-open.org/cti/stix/v2.1/os/stix-v2.1-os.html) provided by [OASIS Open](https://www.oasis-open.org/).
A functional version of the tools is available at [this link](https://cyberactive.performanceware.com.br/).

# Research paper
Find more information about the internals of cyberaCTIve [in this paper deposited at arXiv](https://arxiv.org/abs/2204.03676) entitled _"cyberaCTIve: a STIX-based Tool for Cyber Threat Intelligence in Complex Models"_, by Czekster, Metere, and Morisset (2022).

# Installing the tool
Find information on file `_README.first` in this repository as well as requirements for running it stand-alone.

# Funding
This research is funded by the Industrial Strategy Challenge Fund and EPSRC, [EP/V012053/1](https://gow.epsrc.ukri.org/NGBOViewGrant.aspx?GrantRef=EP/V012053/1), through the [Active Building Centre - Research Programme (ABC RP)](https://abc-rp.com/).

# Technical details
The tool is coded in PHP (PHP Hypertext Pre-Processor) programming language (three-layer application), to run in Apache (or any other application server), and with the help of a Database Management System (DBMS).
It uses two JSON files with the STIX&trade; specification for the parameters and required data types:
1. [STIX2.1.json](https://github.com/czekster/cyberactive/blob/main/json/STIX2.1.json), created based on STIX&trade; specification
2. [STIX2.1-vocabularies.json](https://github.com/czekster/cyberactive/blob/main/json/STIX2.1-vocabularies.json), also created based on STIX&trade; documentation on vocabularies, types, and identifiers.
  
# Requirements and initial steps
- For this application you will require: PHP7+ , MySQL , Apache
  - optionally, you may want to install [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)
  - for MS-Windows, one could also employ [WAMP](https://www.wampserver.com/en/)
- Rename `Properties-sample.php` to `Properties.php`
  - change to your settings
  - ALL properties must be set, for DBMS connection and for sendmail (to allow password retrieval)

# Researchers involved
- Ricardo M. Czekster, Lecturer in Computing, School of Informatics and Digital Engineering, Aston University
- Roberto Metere, Research Associate, School of Computing, Newcastle University and The Alan Turing Institute, London
- Charles Morisset, Senior Lecturer in Security, School of Computing, Newcastle University

