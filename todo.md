# Functional

Important
------------------------------------
- [x] On payment list show only members that have at least one active month in period.
      Basically remove members that don't have active month in period
- [x] Show one month in future on payments and attendance page
- [x] Add option to display PDF documents instead of only download
- [x] Find a better way to save payments and attendance data in database (currently it's serialized string)
- [x] Allow trainers to change their profile details (but not type, only admin can do that)
- [x] Add email field to members form
- [x] Option to search members by email (maybe display email in listing)
- [x] Cron job that will delete obsolete history items (for example older than 2 months)
- [x] Manage members payments and attendance from the edit page (per member, not per group)
- [x] There needs to be a way to know in what group member was in every moment. 
      Make that remember in date history database table and implement where needed
- [x] When new member is created don't save current date to date_history table but instead use date of subscription
- [x] When getting group members for payments and attendance first filter by date_history table and then check if members is valid
      This way we will make number of required database queries way lower than it is now
- [x] Integrate toastr messages (http://codeseven.github.io/toastr/)
- [ ] Page to see when are members due to renew doctors check (for all members)
- [x] For trainer type of users add back link to edit groups on group listing
- [x] Add link under profile dropdown of each user type to directly go and fill attendance details
- [ ] Make dashboards trainer specific (show only group members) and after this enable dashboard to all user types
- [ ] Move configuration options to laravel's .env files
- [x] Change member and user attendance page to have tabs for each year to be easier to use
- [x] Change member and user attendance forms to save all data once submitted instead one of submitting one by one
      
Less Important
------------------------------------
- [ ] Ability to delete every resource type from it's edit page (place a delete button on top or bottom of the page)
- [ ] Migrate to postgres SQL at some time (just because it's cool so no rush there)
- [ ] Add Clubs resource to database table (Clubs are created manually)
    - [ ] Add club_id to every database table
    - [ ] When user is logged in, search for it's club and pull only corresponding details
    - [ ] Find a way to do this on most generic way possible, without having to rewrite every database query out there
- [x] Implement caching mechanism. This is very important but should be done correctly
    - [x] Add option to clear cache (under Settings drop down menu)
    - [x] Payed string caching, as this is most important thing for now
    - [x] Cache dashboard data for 24 hours
 
# Visual

- [x] Add plugin for nicer tooltips
- [x] Change confirmation popup to use bootstrap popups rather than alert windows
- [x] Mark current month in green on payments and attendance page
- [x] Hide DOB and DOS and DOC from member listing table on smaller screen resolutions
- [ ] Alert user when trying to leave page and form is dirty


# Charts (not a priority just nice to have feature)

- [+] New members per year / month
- [+] Member years in percents
- [+] member years grouped by year of subscription
- [ ] Total payments per month
- [ ] Monthly new inactive members
- [x] Total members
- [x] Total active members
- [x] Incoming birthdays
