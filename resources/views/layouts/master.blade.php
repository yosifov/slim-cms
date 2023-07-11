<!DOCTYPE html>
<html lang="{{ isset($locale) ? $locale : 'en' }}">
	@include ("partials.head")
	<body>
		@include ("partials.header")
		
		@yield ("content")

		@include ("partials.footer")
    </body>
</html>