<?php

/*
 * The Yume Framework Translations.
 *
 * This is a translation for English.
 * This is just include all Stream Error messages.
 *
 * @author Ari Setiawan (hxAri)
 * @github https://github.com/hxAri
 * @source https://github.com/yfure/framework/blob/main/src/Furemuwaku/Locale/Translation/en/Stream.php
 * @e-mail hxari@proton.me
 *
 */
return([
	"io.stream.StreamError" => [
		"DetachError" => "Stream {} has been detach, the stream is useless",
		"FreadError" => "An error occurred when reading the {} stream",
		"FseekError" => "An error occurred in Stream {} when re-changing the pointer to {} with whence {}",
		"FtellError" => "An error occurred in Stream {} when determine position",
		"FwriteError" => "An error occurred in Stream {} when writing content",
		"LengthError" => "Cannot read stream content with length below zero, {} is given",
		"ReadError" => "Can't read stream {}, because stream is unreadable",
		"ReadContentError" => "An error occurred when getting the content stream {}",
		"SeekError" => "Stream {} cannot seek, because the unseekable stream",
		"StringifyError" => "Failed parse Object Stream {} into string",
		"TellError" => "Cannot determine the position of a Stream {}",
		"WriteError" => "Stream {} cannot be written, because Stream is unwritable"
	],
	"io.stream.StreamBufferError" => [
		"SeekError" => "@yume<fure<io.stream.StreamError<SeekError>>>",
		"TellError" => "@yume<fure<io.stream.StreamError<TellError>>>"
	]
])

?>
