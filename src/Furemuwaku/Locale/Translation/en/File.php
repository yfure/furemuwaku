<?php

/*
 * The Yume Framework Translations.
 *
 * This is a translation for English.
 * This is just include all File Error messages.
 *
 * @author Ari Setiawan (hxAri)
 * @github https://github.com/hxAri
 * @source https://github.com/yfure/framework/blob/main/src/Furemuwaku/Locale/Translation/en/File.php
 * @e-mail hxari@proton.me
 *
 */
return([
	"io.file.FileError" => [
		"CopyError" => "Failed copy file from {} to {}",
		"FileError" => "Target file {} is not file type",
		"MoveError" => "Failed move file from {} to {}",
		"ModeError" => "Failed open file {}, invalid fopen mode for {}",
		"NameError" => "The file name {} is invalid",
		"NotFoundError" => "No such file {}",
		"OpenError" => "Failed open file {}",
		"PathError" => "Failed open file.{} because directory {} not found",
		"ReadError" => "An error occurred while reading the contents of the file {}",
		"WriteError" => "An error occurred while writing the contents of the file {}"
	],
	"io.file.FileNotFoundError" => [
		"NotFoundError" => "@yume<fure<io.file.FileError<NotFoundError>>>"
	]
])

?>
