include make/*.mk
.DEFAULT_GOAL:=help


update: init vendor update_symfony build test-unit