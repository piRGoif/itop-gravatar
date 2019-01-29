# Gravatar support for iTop

## Description
This extension adds [Gravatar](http://www.gravatar.com/) support for [iTop](https://www.combodo.com/itop) : when installed, it changes the
Person.picture attribute HTML rendering to use a Gravatar call using the Person.email value as email.

This means that for every Person object with no picture defined, the image will be get from Gravatar service. This will change rendering 
in both lists and form details.


## Requirements

iTop v2.7.0 at least : the code needs the new \AttributeImage::GetAttributeImageFileUrl protected method. This method was [added in 2.6.0]() 
as private, but its visibility was changed to protected [in 2.7.0](https://github.com/combodo/itop/commit/6bbc543ac14e1884fe009b3fd313d4f7ab326fde).


## Known problems
The default image won't be displayed if used from a private server.


## How it works
The extensions adds a new AttributeGravatarImage object, and overrides the Person::GetAsHTML method to use the new attribute def implementation.

The Gravatar URL generation is done using [Ember Labs GravatarLib](https://github.com/emberlabs/gravatarlib/), thanks to them for this 
wonderful library !

A simplier way would have been to just redefine the Person.picture attribute type with XML (from AttributeImage to 
AttributeGravatarImage). 
But unfortunately this wouldn't work (getting a mandatory attribute missing error) : most of AttributeDefinition processing
 is hard-coded... Here the error would be raised due to a AttributeImage test in \MFCompiler::CompileClass. 