## ClassGizmo - Integrated Academic Systems With Behavioral Reward Systems 
==========

#### Requirements
* Apache Web Server
* Php 5.4 (or greater)
* MySQL Database
* Git (Install Git-1.9.4-preview20140815.exe or greater)
* Composer 

#### Installation
  1. Create Directory Under /xampp/htdocs/[gizmo]
  2. Change active directory to /xamp/htdocs/[gizmo]
  3. git clone https://github.com/peterAK/PGS-GIZMO.git .
  4. c:\>composer install
  

#### Apache SetUp
  Add Virtual Host to: httpd-vhosts.conf<br/>
  <pre>
    < VirtualHost *:80 >
        DocumentRoot "c:/xampp/htdocs/[your directory]/public_html"
        ServerName [domain.name]
        DirectoryIndex app.php
        < Directory "c:/xampp/htdocs/[your directory]/public_html" >
          AllowOverride All
          Allow from All
        < /Directory >
    < /VirtualHost >
  </pre>

#### Windows Host
  Edit your host file: C:\Windows\System32\drivers\etc\hosts, add new line:
  127.0.0.1	[domain.name]
  

#### Git Workflow:
  1. Create a new branch whenever working on something new <br/>
      git checkout -b 'feature/[featurename]'
  2. After working on adding/editing the files, do:<br/>
      git commit -m "descriptive comment on what you do"
  3. git push origin 'feature/[featurename]'
  4. create a PR [pull request] on git
  5. After being reviewed, this may be merged to master
  6. To ensure you have the latest code, do:<br/>
      git checkout master<br/>
      git fetch<br/>
      git pull origin master
  7. To rebase your branch:<br/>
      git checkout [branchname]<br/>
      git rebase master
