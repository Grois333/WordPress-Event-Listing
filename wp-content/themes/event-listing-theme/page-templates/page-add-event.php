<?php

/**
 * Template Name: Add Event
 */

get_header();
?>

<form id="event-form" class="add-event-form">
    <h1>Add New Event</h1>
    <label for="event_title">Event Title</label>
    <input type="text" name="event_title" id="event_title" required>

    <label for="event_date">Event Date</label>
    <input type="date" name="event_date" id="event_date" required>

    <label for="event_venue">Event Venue</label>
    <input type="text" name="event_venue" id="event_venue" required>

    <button type="submit">Add Event</button>
</form>
<div id="loader" style="display: none;">Loading...</div>
<div id="form-response"></div>


<?php
get_footer();
