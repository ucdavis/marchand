class UserMailer < ApplicationMailer
    # Subject can be set in your I18n file at config/locales/en.yml
    # with the following lookup:
    #
    #   en.lesson_mailer.lesson_request.subject
    #
    def lesson_request(lesson, customer_email)
      @lesson = lesson
      @customer_email = customer_email

      # TODO: change to: historyproject@ucdavis.edu
      mail to: "sadaf053@gmail.com",
        subject: "New Lesson Request from Marchand"
    end
end
