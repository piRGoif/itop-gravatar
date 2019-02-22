# Gravatar support for iTop

## 👤 Description
This extension adds [Gravatar](http://www.gravatar.com/) support for [iTop](https://www.combodo.com/itop) : when installed, it changes the
Person.picture attribute HTML rendering. If no picture are already loaded, then a Gravatar call using the Person.email value as 
email is done.

This means that for every Person object with no picture defined, the image will be loaded from Gravatar service. This will change rendering 
in all iTop screens like lists, form details, extended portal.


## ✔️ Requirements

iTop version 2.3.0 (enhanced portal support).


## 💣 Known problems

* There should not be any other overrides of the \Person::Get method !
* ManageBricks won't display empty Person.picture correctly anymore (broken image instead of the gravatar or default image)
* The default image won't be displayed if used from a private server (not visible from the Gravatar service).


## 🔧 How it works
In an ideal world, just adding a new specific attribute type, and overriding the datamodel class attribute type with XML would be enough.
.. But it's not as simple as that 😱 ! Actually lots of the code around attributes is hard coded in iTop, for example in the 
\MFCompiler::CompileClass...

The extensions adds :

* a new ormGravatarImage object 
* overrides Person::Get method
* overrides 'p_object_document_display' enhanced portal route

So for the specific Person.picture attribute, a new value object is returned, implementing a custom URL generation code.  

The Gravatar URL generation is done using [Ember Labs GravatarLib](https://github.com/emberlabs/gravatarlib/), thanks to them for this 
wonderful library 👍 !
