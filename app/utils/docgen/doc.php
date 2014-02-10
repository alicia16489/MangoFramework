Conventions:
    - Method:
        /**
         * Your description
         * ... Your Description
         *
         * @type: method visibility
         * @param: type $name (description)
         * @return: type $name (description)
         */
         (static) visibility (static) function hello($name) { OR
         (static) visibility (static) function hello($name)
         {
         }

    - :
        /**
         * Your description
         * ... Your Description
         *
         * @type: attribute visibility (static) type
         * @name: $name
         */
        (static) visibility (static) $name = value;

Details:
    - Skip a comment:
        You can add a "!" at the second line of your comment to skip it
        /**
         *!
         * Your description
         * ... Your Description
         *
         * @type: method (visibility(public, protected or private))
         * @param: type $name (description)
         * @return: type $name (description)
         */

    /!\ every arguments with () are not mandatory
