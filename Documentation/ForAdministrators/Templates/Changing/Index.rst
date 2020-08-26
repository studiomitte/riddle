.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _changing_templates:

Changing the output
===================
The riddle.com API returns the HTML which is used to show the riddle in the frontend.
If you want to modify the output nevertheless, change the TypoScript used by default which is shown below and provide a different `userFunc`.

.. code-block:: typoscript

   tt_content.list.20.riddle_riddle = USER
   tt_content.list.20.riddle_riddle {
       userFunc = StudioMitte\Riddle\Plugin\RiddlePlugin->run
   }

