# Gravatar support for iTop

## 👤 Description
This extension adds [Gravatar](http://www.gravatar.com/) support for [iTop](https://www.combodo.com/itop) : when installed, it changes the
Person.picture attribute HTML rendering. If no picture are already loaded, then a Gravatar call using the Person.email value as 
email is done.

This means that for every Person object with no picture defined, the image will be loaded from Gravatar service. This will change rendering 
in both lists and form details.


## ✔️ Requirements

iTop v2.7.0 at least : the code needs the new \AttributeImage::GetAttributeImageFileUrl protected method. This method was [added in 2.6.0]() 
as private, but its visibility was changed to protected [in 2.7.0](https://github.com/combodo/itop/commit/6bbc543ac14e1884fe009b3fd313d4f7ab326fde).


## 💣 Known problems
The default image won't be displayed if used from a private server (not visible from the Gravatar service).


## 🔧 How it works
In an ideal world, just adding a new specific attribute type, and overriding the datamodel class attribute type with XML would be enough.
.. But it's not as simple as that 😱 ! Actually lots of the code around attributes is hard coded in iTop, for example in the 
\MFCompiler::CompileClass...

The extensions adds :

* a new ormGravatarImage object 
* overrides Person::Get method

So for the specific Person.picture attribute, a new value object is returned, implementing a custom URL generation code.

The Gravatar URL generation is done using [Ember Labs GravatarLib](https://github.com/emberlabs/gravatarlib/), thanks to them for this 
wonderful library 👍 !
