<!DOCTYPE html>
<html lang="{{ isset($locale) ? $locale : 'en' }}">
	@include ("partials.head")
	<body>
		@yield ("content")

		@include ("partials.footer")
    </body>
</html>