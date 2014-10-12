Todo
====================================

Functional
------------------------------------
- [x] On payment list show only members that have at least one active month in period.
      Basically remove members that don't have active month in period
- [x] Show one month in future on payments and attendance page
- [x] Add option to display PDF documents instead of only download
- [x] Find a better way to save payments and attendance data in database
    (currently it's serialized string)
- [x] Allow trainers to change their profile details (but not type, only admin can do that)
- [ ] Migrate to postgres SQL at some time (just because it's cool so no rush there)
- [x] Add email field to members form
- [x] Option to search members by email (maybe display email in listing)
- [ ] Ability to delete every resource type from it's edit page (place a delete button on top or bottom of the page)
- [x] Cron job that will delete obsolete history items (for example older than 2 months)
- [ ] Add Clubs resource to database table (Clubs are created manually)
- [ ] Add club_id to every database table
- [ ] When user is logged in, search for it's club and pull only corresponding details
      Find a way to do this on most generic way possible, without having to rewrite every database query out there
- [x] Manage members payments and attendance from the edit page (per member, not per group)
- [x] There needs to be a way to know in what group member was in every moment. 
      Make that remember in date history database table and implement where needed
      

Visual
------------------------------------
- [x] Add plugin for nicer tooltips
- [x] Change confirmation popup to use bootstrap popups rather than alert windows
- [x] Mark current month in green on payments and attendance page
- [ ] Alert user when trying to leave page and form is dirty
- [x] Hide DOB and DOS and DOC from member listing table on smaller screen resolutions

Charts (not a priority just nice to have feature)
------------------------------------
- [ ] New members per year / month
- [ ] Members that are still active in new year
- [ ] Monthly new inactive members
