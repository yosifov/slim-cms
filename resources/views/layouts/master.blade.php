<!DOCTYPE html>
<html lang="{{ get_locale() }}">
	@include ("partials.head")
	<body>
		@include ("partials.header")
		
		@yield ("content")

		@include ("partials.footer")
    </body>
</html>